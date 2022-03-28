<?php
include 'inc/init.inc.php';
include 'inc/functions.inc.php';


// TEST
// echo 'TEST';
// var_dump($...);
// echo '<pre>'; print_r($...); echo '</pre>';


// isset sur le produit
if (isset($_GET['id_produit'])) {
    $recup_produit = $pdo->prepare("SELECT * FROM salle, produit WHERE id_produit = :id_produit AND salle.id_salle = produit.id_salle");
    $recup_produit->bindParam(':id_produit', $_GET['id_produit'], PDO::PARAM_STR);
    $recup_produit->execute();

    // on vérifie si on a une ligne (si on a bien récupéré un article)
    if ($recup_produit->rowCount() < 1) {
        // on redirige vers index
        header('location:index.php');
    }
} else {
    header('location:index.php');
}


// On traite la ligne avec fetch pour récupérer la liste des produits dans la BDD
$infos_produit = $recup_produit->fetch(PDO::FETCH_ASSOC);



// On récupère l'ID salle
$id_salle = $infos_produit['id_salle'];

// On récupère l'ID membre
if ( user_is_connected() || user_is_admin() ){
    $id_membre = $_SESSION['membre']['id_membre'];
}

// On récupère l'ID produit
$id_produit = $_GET['id_produit'];

// On récupère l'état du produit
$etat = $infos_produit['etat'];
// echo $etat;

// On récupère la date pour afficher la durée de réservation
$calcul_arrivee = strtotime($infos_produit['date_arrivee']);
$calcul_depart = strtotime($infos_produit['date_depart']);
$duree = round(($calcul_depart - $calcul_arrivee)/60/60/24,0);



// ENREGISTREMENT D'UNE RESERVATION
//--------------------------------------------------------------------------

if( isset($_POST['id_membre']) && isset($_POST['id_produit']) ) {



    // Variable de controle
    $erreur = false;

    // Contrôle sur la disponibilité du produit en BDD
    $verif_produit = $pdo->prepare("SELECT * FROM commande WHERE id_produit = :id_produit");
    $verif_produit->bindParam(':id_produit', $id_produit, PDO::PARAM_STR);
    $verif_produit->execute();


    // Contrôle sur la disponibilité du produit en BDD
    if( $verif_produit->rowCount() > 0 || $etat == 'reservation' ) {
        $msg .= '<div class="alert alert-danger m-4">Cette salle est déjà réservée.</div>';

        $erreur = true;
    }




    if($erreur == false) {
    // On insert dans commande la réservation
    $reservation = $pdo->prepare("INSERT INTO commande (id_membre, id_produit, date_enregistrement) VALUES (:id_membre, :id_produit, NOW())");
    $reservation->bindParam(':id_membre', $id_membre, PDO::PARAM_STR);
    $reservation->bindParam(':id_produit', $id_produit, PDO::PARAM_STR);
    $reservation->execute();

    // On passe l'état du produit en 'reservation'
    $etat = 'reservation';
    $reservation = $pdo->prepare("UPDATE produit SET etat = :etat WHERE id_produit = :id_produit");
    $reservation->bindParam(':id_produit', $id_produit, PDO::PARAM_STR);
    $reservation->bindParam(':etat', $etat, PDO::PARAM_STR);
    $reservation->execute();

    $msg .= '<div class="alert alert-success m-4">Félicitation, vous venez de réserver votre salle.</div>';

    }

}



// ENREGISTREMENT DES AVIS
//--------------------------------------------------------------------------

$note = '';
$commentaire = '';

// Le formulaire est validé >> on vérifie les champs
if( isset($_POST['note']) && isset($_POST['commentaire']) ) {


    $note = trim($_POST['note']);
    $commentaire = trim($_POST['commentaire']);


    // Variable de controle
    $erreur = false;

    // NOTE
    if( empty($note) || !is_numeric($note) || $note > 5) {
        $msg .= '<div class="alert alert-danger m-4">La note est obligatoire.</div>';
        $erreur = true;
    }

    // COMMENTAIRE
    if( empty($commentaire) ) {
        $msg .= '<div class="alert alert-danger m-4">Le commentaire est obligatoire.</div>';
        $erreur = true;
    }
    

    // Si tout est OK on lance l'enregistrement sur la BDD
    if($erreur == false) {

        $enregistrement = $pdo->prepare("INSERT INTO avis (id_salle, id_membre, note, commentaire, date_enregistrement) VALUES (:id_salle, :id_membre, :note, :commentaire, NOW())");

        $enregistrement->bindParam(':id_salle', $id_salle, PDO::PARAM_STR);
        $enregistrement->bindParam(':id_membre', $id_membre, PDO::PARAM_STR);
        $enregistrement->bindParam(':note', $note, PDO::PARAM_STR);
        $enregistrement->bindParam(':commentaire', $commentaire, PDO::PARAM_STR);
        $enregistrement->execute();

        $msg .= '<div class="alert alert-success m-4">Merci d\'avoir donné votre avis.</div>';

    }


} // fin des isset avis



// RECUPERATION DES AVIS EN BDD POUR AFFICHAGE
//--------------------------------------------------------------------------
if (isset($_GET['id_produit'])) {
$liste_avis = $pdo->prepare("SELECT * FROM salle, membre, avis, produit WHERE id_produit = :id_produit AND salle.id_salle = produit.id_salle AND salle.id_salle = avis.id_salle AND membre.id_membre = avis.id_membre");
$liste_avis->bindParam(':id_produit', $_GET['id_produit'], PDO::PARAM_STR);
$liste_avis->execute();
}


// RECUPERATION DU TOTAL DE COMMENTAIRE POUR AFFICHAGE
//--------------------------------------------------------------------------
if (isset($_GET['id_produit'])) {
$total_avis = $pdo->prepare("SELECT COUNT(*) AS nombre FROM salle, membre, avis, produit WHERE id_produit = :id_produit AND salle.id_salle = produit.id_salle AND salle.id_salle = avis.id_salle AND membre.id_membre = avis.id_membre");
$total_avis->bindParam(':id_produit', $_GET['id_produit'], PDO::PARAM_STR);
$total_avis->execute();
$info_avis = $total_avis->fetch(PDO::FETCH_ASSOC);
$number_avis = $info_avis['nombre'];
}
// RECUPERATION DES NOTES POUR AFFICHAGE
//--------------------------------------------------------------------------
if (isset($_GET['id_produit'])) {
// $recup_note = $pdo->prepare("SELECT note FROM salle, membre, avis, produit WHERE id_produit = :id_produit AND salle.id_salle = produit.id_salle AND salle.id_salle = avis.id_salle AND membre.id_membre = avis.id_membre");
// $recup_note->bindParam(':id_produit', $_GET['id_produit'], PDO::PARAM_STR);
// $recup_note->execute();
// $info_note = $recup_note->fetch(PDO::FETCH_ASSOC);
// $notevisu = $info_note['note'];

$recup_note_moy = $pdo->prepare("SELECT ROUND(AVG(note), 1) AS nombre FROM avis, salle, produit WHERE produit.id_produit = :id_produit AND avis.id_salle = salle.id_salle AND salle.id_salle = produit.id_salle");
$recup_note_moy->bindParam(':id_produit', $_GET['id_produit'], PDO::PARAM_STR);
$recup_note_moy->execute();
$liste_note_moy = $recup_note_moy->fetch(PDO::FETCH_ASSOC);
$note_moy = $liste_note_moy['nombre'];

}



// Les affichages dans la page commencent depuis la ligne suivante :
include 'inc/header.inc.php';
include 'inc/nav-front.inc.php';

?>



    <!-- MAIN -->
    <main class="container-fluid" id="main-product">
        <div class="row big-card">
            <div class="col-lg-8 p-0 img-big-card">
                <span class="img-etat p-4">
                    <?php 
                    if ( $infos_produit['etat'] == 'reservation') {
                        echo 'Déjà réservé';
                    }
                    ?>
                </span>
                <img src="<?php echo URL . 'assets/img_salles/' . $infos_produit['photo']; ?>" alt="Image de la salle : <?php echo $infos_produit['titre']; ?>" class="w-100">
            </div>
            <div class="col-lg-4 p-0 text-big-card">
                <!-- <div class="product-header-info px-4 pt-4"> -->
                <div class="product-header-info p-4">
                    <h2><?php echo $infos_produit['ville']; ?></h2>
                    <div class="stars"><?php stars($note_moy); ?></div>
                </div>
                <h1 class="ps-4">Salle<br><?php echo $infos_produit['titre']; ?></h1>
                <p class="pe-4 pt-4 text-end under-title">Le lieu idéal pour votre <span><?php echo $infos_produit['categorie']; ?>.</span></p>
                <?php echo $msg;  ?>
                <div class="product-middle-info px-4 mt-5 mb-3">
                    
                    <span class="product-price"><?php echo $infos_produit['prix']; ?> €</span>
                
                <?php if( user_is_connected() ) { ?>

                    <a href="#" class="notes" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Noter cette salle<i class="far fa-envelope ms-2"></i></a>
                </div>
                <form method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id_membre" value="id_membre">
                    <input type="hidden" name="id_produit" value="id_produit">
                    <div class="px-4 mb-5">                                
                        <input type="submit" class="btn btn-mycolor text-white w-100" id="reservation" name="reservation" value="Réserver">
                    </div>
                </form>

                <!-- Modal -->
                <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title" id="staticBackdropLabel">Partager votre avis</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <form method="post" enctype="multipart/form-data">
                                    <div class="col-sm-12">
                                        <div class="mb-3">
                                            
                                            <select class="form-control" id="note" name="note">
                                                <option selected>Noter la salle <?php echo $infos_produit['titre']; ?></option>
                                                <option>1</option>
                                                <option>2</option>
                                                <option>3</option>
                                                <option>4</option>
                                                <option>5</option>
                                                
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <textarea class="form-control" id="commentaire" rows="6" name="commentaire" placeholder="Ecrire en commentaire"></textarea>
                                        </div>
                                        <div>                                
                                            <input type="submit" class="btn btn-mycolor text-white w-100" id="enregistrement" name="enregistrement" value="Envoyer">
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>


                <?php } else { ?>

                    <a class="notes color-perso-red">Veuillez vous connecter</a>
                </div>
                <form method="post" action="<?php echo URL; ?>connexion.php">
                    <!-- <input type="hidden" name="id_produit" value="<?php echo $infos_produit['id_produit']; ?>"> -->
                    <div class="px-4 mb-5">                                
                        <input type="submit" class="btn btn-mycolor text-white w-100" name="connecter" value="Connecter">
                    </div>
                </form>
                    
                <?php } ?>

                <!-- INFORMATIONS -->
                <h3 class="px-4">Informations</h3>
                <ul class="list-group list-group-flush px-4 pb-4 content-info">
                    <li class="list-group-item"><i class="fas fa-long-arrow-alt-right"></i>
                        <b>Arrivée :</b> <?php echo date('d/m/Y', strtotime($infos_produit['date_arrivee'])); ?>
                    </li>
                    <li class="list-group-item"><i class="fas fa-long-arrow-alt-left"></i>
                        <b>Départ :</b> <?php echo date('d/m/Y', strtotime($infos_produit['date_depart'])); ?>
                    </li>
                    <li class="list-group-item"><i class="fas fa-clock"></i>
                        <?php
                        if ($duree == 1 || $duree == 0 || $duree == -1 ) {
                            echo '<b>Durée :</b> ' . $duree . ' jour';
                        } else {
                            echo '<b>Durée :</b> ' . $duree . ' jours';
                        }
                        ?>
                    </li>
                    <li class="list-group-item"><i class="fas fa-users"></i>
                        <b>Capacité :</b> <?php echo $infos_produit['capacite']; ?> personnes
                    </li>
                    <li class="list-group-item"><i class="fas fa-inbox"></i>
                        <b>Catégorie :</b> <?php echo $infos_produit['categorie']; ?>
                    </li>
                    <li class="list-group-item"><i class="fas fa-map-marker-alt"></i>
                        <b>Adresse :</b> <?php echo $infos_produit['adresse'] . ', ' . $infos_produit['cp'] . ', ' . $infos_produit['ville']; ?>
                    </li>

                </ul>
            </div>
        </div>

        <div class="row p-4">
            <div class="col-lg-8 p-0">
                <h3 class="mb-2">Pourquoi cette salle est unique.</h3>
                <p class="product-description">
                    <?php echo $infos_produit['description']; ?>
                </p>
                <div class="content-equip pb-4">

                    <div class="bloc-icon">
                        <div class="circle-icon"><i class="fas fa-tv"></i></div>
                        <p>Projection<br>
                        support TV</p> 
                    </div>
                    <div class="bloc-icon">
                        <div class="circle-icon"><i class="fas fa-moon"></i></div>
                        <p>Accepte<br>
                        les soirées</p> 
                    </div>
                    <div class="bloc-icon">
                        <div class="circle-icon"><i class="fas fa-mug-hot"></i></div>
                        <p>Boissons<br>
                        chaudes</p> 
                    </div>
                    <div class="bloc-icon">
                        <div class="circle-icon"><i class="fas fa-utensils"></i></div>
                        <p>Repas<br>
                        possible</p> 
                    </div>
                    <div class="bloc-icon">
                        <div class="circle-icon"><i class="fas fa-bolt"></i></div>
                        <p>Fibre<br>
                        optique</p> 
                    </div>
                    <div class="bloc-icon">
                        <div class="circle-icon"><i class="fas fa-restroom"></i></div>
                        <p>Toilettes<br>
                        & papier</p> 
                    </div>
                    <div class="bloc-icon">
                        <div class="circle-icon"><i class="fas fa-wifi"></i></div>
                        <p>WIFI<br>
                        sécurisé</p> 
                    </div>
                    <div class="bloc-icon">
                        <div class="circle-icon"><i class="fas fa-tint"></i></div>
                        <p>Fontaine<br>
                        à eau filtrée</p> 
                    </div>

                </div>
            </div>
            <div class="col-lg-4 p-0">
                <div class="content-map">
                    <!-- GOOGLE MAP -->
                    <div class="border-map">
                        <?php
                        $ville = $infos_produit['ville'];
                        $adresse = $infos_produit['adresse'];
                        $cp = $infos_produit['cp'];
                        $ville_url = str_replace(' ', '+', $ville); 
                        $adresse_url = str_replace(' ', '+', $adresse); 
                        $MapCoordsUrl = urlencode($cp.'+'.$ville_url.'+'.$adresse_url); 
                        ?>
                        <iframe src="http://maps.google.fr/maps?q=<?php echo $MapCoordsUrl; ?>&amp;t=h&amp;output=embed"></iframe>
                        


                        <!-- <iframe width="1000" height="400" id="gmap_canvas" src="https://maps.google.com/maps?q=<?php //echo $infos_produit['ville']; ?>&t=&z=13&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe> -->
                    </div>

                </div>
            </div>


        </div>

        <div class="row p-4 content-com">
            
                <?php
                echo '<h4>Commentaires (' . $number_avis . ')</h4><hr>';
                if ($number_avis == 0){
                    echo '<div class="ps-4 mb-3 header-com">';
                    echo '<div>Il n\'y a actuellement aucun avis sur cette salle.<br>';
                    echo '<i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>';
                    echo '</div>';
                    echo '</div>';
                    echo '<hr>';
                } else {
                    while($ligne = $liste_avis->fetch(PDO::FETCH_ASSOC)) {
                        
                        echo '<div class="ps-4 mb-3 header-com">';
                        echo '<div>' . $ligne['pseudo'] . '<br>';
                        stars($ligne['note']);
                        echo '</div>';
                        echo '<div>' . date('d/m/Y', strtotime($ligne['date_enregistrement'])) . '</div>';
                        echo '</div>';
                        echo '<div class="ps-4 mb-4">';
                        echo $ligne['commentaire'];

                        echo '</div>';
                        echo '<hr>';
                    }
                }

                ?>

        </div>












    </main>

<?php 
include 'inc/footer.inc.php';

