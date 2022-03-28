<?php
include '../inc/init.inc.php';
include '../inc/functions.inc.php';


// TEST
// echo 'TEST';
// var_dump($...);
// echo '<pre>'; print_r($...); echo '</pre>';


// Restriction d'accès, si l'utilisateur n'est pas admin, on le redirige vers connexion.php
if( !user_is_admin() ) {
    header('location:../connexion.php');
    exit(); // bloque l'exécution du code à la suite de cette ligne.
}



//--------------------------------------------------------------------------
// SUPPRESSION DES PRODUITS
//--------------------------------------------------------------------------
if( isset($_GET['action']) && $_GET['action'] == 'supprimer' && !empty($_GET['id_produit']) ) {
    $suppression = $pdo->prepare("DELETE FROM produit WHERE id_produit = :id_produit");
    $suppression->bindParam(':id_produit', $_GET['id_produit'], PDO::PARAM_STR);
    $suppression->execute();
}


//--------------------------------------------------------------------------
// ENREGISTREMENT & MODIFICATION DES PRODUITS
//--------------------------------------------------------------------------


$id_produit = '';
$id_salle = '';
$date_arrivee = '';
$date_depart = '';
$prix = '';
// on set par défaut l'état d'une salle sur 'libre'
$etat = 'libre';



// Le formulaire est validé >> on vérifie tous les champs
if( isset($_POST['id_produit']) && isset($_POST['id_salle']) && isset($_POST['date_arrivee']) && isset($_POST['date_depart']) && isset($_POST['prix']) ) {

    $id_produit = trim($_POST['id_produit']);
    $id_salle = trim($_POST['id_salle']);
    $date_arrivee = trim($_POST['date_arrivee']);
    $date_depart = trim($_POST['date_depart']);
    $prix = trim($_POST['prix']);


    // On change le format de date
    
    $date_arrivee = date('Y-m-d H:i:s ', strtotime($date_arrivee));
    $date_depart = date('Y-m-d H:i:s ', strtotime($date_depart));



    // Pour la modif, récupération de l'id produit
    if( !empty($_POST['id_produit']) ) {
        $id_produit = trim($_POST['id_produit']);
    }

    // Variable de controle
    $erreur = false;



    // Controle sur la date d'arrivée
    if( empty($date_arrivee) ) {
        $msg .= '<div class="alert alert-danger mt-3">Vous n\'avez pas renseigné la date d\'arrivée.</div>';
        // Cas d'erreur 
        $erreur = true;
    }

    // Controle sur la date de départ
    if( empty($date_depart) ) {
        $msg .= '<div class="alert alert-danger mt-3">Vous n\'avez pas renseigné la date de départ.</div>';
        // Cas d'erreur 
        $erreur = true;
    }

    // On vérifie que le prix est bien renseigné
    if( !is_numeric($prix) ) {
        $msg .= '<div class="alert alert-danger mt-3">Vous n\'avez pas renseigné correctement le prix.</div>';
        // Cas d'erreur 
        $erreur = true;
    }


    // ON PEUT ENREGISTRER DANS LA BDD
    if($erreur == false) {

        // Si on modifie un produit
        if( !empty($id_produit) ) {
            $enregistrement = $pdo->prepare("UPDATE produit SET id_salle = :id_salle, date_arrivee = :date_arrivee, date_depart = :date_depart, prix = :prix, etat = :etat WHERE id_produit = :id_produit");
            $enregistrement->bindParam(':id_produit', $id_produit, PDO::PARAM_STR);
            

        } else {
            $enregistrement = $pdo->prepare("INSERT INTO produit (id_salle, date_arrivee, date_depart, prix, etat) VALUES (:id_salle, :date_arrivee, :date_depart, :prix, :etat)");
            
        }

        $enregistrement->bindParam(':id_salle', $id_salle, PDO::PARAM_STR);
        $enregistrement->bindParam(':date_arrivee', $date_arrivee, PDO::PARAM_STR);
        $enregistrement->bindParam(':date_depart', $date_depart, PDO::PARAM_STR);
        $enregistrement->bindParam(':prix', $prix, PDO::PARAM_STR);
        $enregistrement->bindParam(':etat', $etat, PDO::PARAM_STR);
        $enregistrement->execute();

        $msg .= '<div class="alert alert-success mt-3">Vous avez enregistré un nouveau produit.</div>';
    }



} // Fin des isset


// RECUPERATION DES INFOS DE PRODUITS A MODIFIER
//--------------------------------------------------------------------------
if( isset($_GET['action']) && $_GET['action'] == 'modifier' && !empty($_GET['id_produit']) ) {

    $recup_infos = $pdo->prepare("SELECT * FROM produit WHERE id_produit = :id_produit");
    $recup_infos->bindParam(':id_produit', $_GET['id_produit'], PDO::PARAM_STR);
    $recup_infos->execute();

    $infos_produit = $recup_infos->fetch(PDO::FETCH_ASSOC);

    $id_produit = $infos_produit['id_produit'];
    $id_salle = $infos_produit['id_salle'];
    $date_arrivee = $infos_produit['date_arrivee'];
    $date_depart = $infos_produit['date_depart'];
    $prix = $infos_produit['prix'];

}


// RECUPERATION DES PRODUITS EN BDD POUR AFFICHAGE
//--------------------------------------------------------------------------
$liste_produits = $pdo->query("SELECT * FROM salle, produit WHERE salle.id_salle = produit.id_salle");




// Les affichages dans la page commencent depuis la ligne suivante :
include '../inc/header.inc.php';
include '../inc/nav-back.inc.php';

?>




    <!-- HEADER -->
    <header class="container mt-5">
        <div class="pt-5 rounded col-12">
            <h2 class="mt-5">Gestion des produits</h1>
            <p class="lead">Créer vos produits dans cet espace : Dates de locations disponibles et prix correspondant.</p>
            <!-- variable destinée à afficher des messages utilisateur  -->
            <?php echo $msg;  ?>
        </div>
    </header>


    <!-- MAIN -->
    <main class="container main-back mt-5" id="main-back-produits">

        <!-- ENREGISTREMENT DES PRODUITS -->
        <div class="row">

            <div class="col-12 mx-auto mb-5">
                <form method="post" action="" enctype="multipart/form-data">

                    <!-- variable cachée pour la modification -->
                    <input type="hidden" name="id_produit" value="<?php echo $id_produit; ?>">

                    <div class="row">

                        <div class="col-sm-12">
                            <div class="input-group mb-3">
                                <label for="id_salle" class="input-group-text">Salle</label>
                                <select class="form-control" id="id_salle" name="id_salle">
                                    <?php
                                    $liste_infos_salle = $pdo->query("SELECT * FROM salle");
                                    while ($infos_salle = $liste_infos_salle->fetch(PDO::FETCH_ASSOC)) {
                                        echo '<option value="' . $infos_salle['id_salle'] . '" ';                                    
                                        if($infos_salle['id_salle'] == $id_salle) { echo 'selected';};
                                        echo '>' . $infos_salle['id_salle'] . ' - Salle ' .  
                                        $infos_salle['titre'] . ' - ' . 
                                        $infos_salle['adresse'] . ', ' . 
                                        $infos_salle['cp'] . ', ' . 
                                        $infos_salle['ville'] . ' - ' . 
                                        $infos_salle['capacite'] . ' personnes - ' . 
                                        $infos_salle['categorie'] . 
                                        '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="date_arrivee" class="form-label">Date d'arrivée</label>
                                <input type="text" class="form-control" id="date_arrivee" name="date_arrivee" autocomplete="off" value="<?php echo $date_arrivee; ?>">
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-control" id="prix" name="prix" value="<?php echo $prix; ?>" placeholder="Prix">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="date_depart" class="form-label">Date de départ</label>
                                <input type="text" class="form-control" id="date_depart" name="date_depart" autocomplete="off" value="<?php echo $date_depart; ?>">
                            </div>
                            <div class="mb-3">                                
                                <input type="submit" class="btn btn-mycolor text-white w-100" id="enregistrement" name="enregistrement" value="Enregistrer">
                            </div>
                        </div>

                    </div>

                </form>
            </div>

        </div>


        <!-- AFFICHAGE DES PRODUITS -->
        <div class="row">
                <div class="col-12 mx-auto bloc-table">
                    <table class="table table-bordered bg-white">
                        <tr class="bg-perso-green text-white">
                            <th>Id produit</th>
                            <th>Date d'arrivée</th>
                            <th>Date de départ</th>
                            <th>Id salle</th>
                            <th>Photo</th>
                            <th>Prix</th>
                            <th>Etat</th>
                            <th>Actions</th>
                        </tr>

                        <?php
                            while($ligne = $liste_produits->fetch(PDO::FETCH_ASSOC)) {
                                echo '<tr>';
                                echo '<td>' . $ligne['id_produit'] . '</td>';
                                echo '<td>' . $ligne['date_arrivee'] . '</td>';
                                echo '<td>' . $ligne['date_depart'] . '</td>';
                                echo '<td>' . $ligne['id_salle'] . ' - ' . $ligne['titre'] . '</td>';
                                echo '<td class="text-center"><img src="' . URL . 'assets/img_salles/' . $ligne['photo'] . '" width="80"></td>';
                                echo '<td>' . $ligne['prix'] . ' €</td>';
                                echo '<td>' . $ligne['etat'] . '</td>';
                                echo '
                                <td class="text-center"><a href="?action=modifier&id_produit=' . $ligne['id_produit'] . '" class="btn-modif"><i class="fas fa-edit"></i></a>';
                                echo '<a href="?action=supprimer&id_produit=' . $ligne['id_produit'] . '" class="btn-supr confirm_delete" ><i class="far fa-trash-alt"></i></a></td>';

                                echo '</tr>';

                            }
                        ?>
                    </table>
                </div>
        </div>


        
    </main>



<?php 
include '../inc/footer.inc.php';

