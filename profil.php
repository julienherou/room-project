<?php
include 'inc/init.inc.php';
include 'inc/functions.inc.php';


// TEST
// echo 'TEST';
// var_dump($...);
// echo '<pre>'; print_r($...); echo '</pre>';

// Si l'utilisateur n'est pas connecté, on redirige vers connexion.php
if( !user_is_connected() ) {
    header('location:connexion.php');
}

// On modifie les variables pour l'affichage
if($_SESSION['membre']['civilite'] == 'm') {
    $civilite = 'homme';
} elseif ($_SESSION['membre']['civilite'] == 'f'){
    $civilite = 'femme';
} else{
    $civilite = 'non binaire';
}

if($_SESSION['membre']['statut'] == 2) {
    $statut = 'vous êtes administrateur';
} else {
    $statut = 'vous êtes membre';
}


// On récupère l'ID_membre
// if ( user_is_connected() || user_is_admin() ){
//     $id_membre = $_SESSION['membre']['id_membre'];
// }


// RECUPERATION DES COMMANDES POUR AFFICHAGE
//--------------------------------------------------------------------------
$liste_commande = $pdo->query("SELECT * FROM salle, produit, membre, commande WHERE salle.id_salle = produit.id_salle AND membre.id_membre = commande.id_membre AND produit.id_produit = commande.id_produit");



// Les affichages dans la page commencent depuis la ligne suivante :
include 'inc/header.inc.php';
include 'inc/nav-front.inc.php';

?>


    <!-- HEADER -->
    <header class="container mt-5 header-profil">
        <div class="pt-5 rounded col-12">
            <h1 class="my-5">Mon compte</h1>
            <!-- variable destinée à afficher des messages utilisateur  -->
            <?php echo $msg;  ?>
        </div>
    </header>


    <!-- MAIN -->
    <main class="container" id="main-profil">


        <div class="row">

            <!-- Historique des commandes -->
            <div class="col-lg-6 mt-2 mb-5 mx-auto">
                <ul class="list-group list-group-profil">
                        <li class="list-group-item bg-perso-red text-white tab-title" aria-current="true">Historique des commandes</li>

                        <?php
                        while($ligne = $liste_commande->fetch(PDO::FETCH_ASSOC)) {
                            
                            // On affiche uniquement les commandes du membre connecté 
                            if( $ligne['id_membre'] == $_SESSION['membre']['id_membre'] ) {
                                echo '<li class="list-group-item li_flex double-ligne"><b>Commande N° ' . $ligne['id_commande'];
                                echo ' - Effectuée le ' . date('d/m/Y', strtotime($ligne['date_enregistrement']));
                                echo ' pour un total de ' . $ligne['prix'] . ' €</b><br>';
                                echo '<span class="text-commande">Salle N° ' . $ligne['id_salle'] . ' ' . $ligne['titre'];
                                echo ' - Réservée du ' . date('d/m/Y', strtotime($ligne['date_arrivee']));
                                echo ' au ' . date('d/m/Y', strtotime($ligne['date_depart'])) . '</span>';
                                echo '</li>';

                            }
                            // else {
                            //     echo '<li class="list-group-item li_flex">Aucune commande n\'a encore été réalisée.</li>';
                            //     break;
                            // }

                        }
                        ?>

                </ul>
            </div>

            <div class="col-lg-4 mt-2 mb-5 mx-auto tab-details">

                <ul class="list-group list-group-profil">
                    <li class="list-group-item bg-perso-red text-white tab-title" aria-current="true">Détails du compte</li>
                    
                    <li class="list-group-item li_flex"><b>Client N°</b><span><?php echo $_SESSION['membre']['id_membre']; ?></span></li>

                    <li class="list-group-item li_flex"><b>Pseudo</b><span><?php echo $_SESSION['membre']['pseudo']; ?></span></li>

                    <li class="list-group-item li_flex"><b>Nom</b><span><?php echo $_SESSION['membre']['nom']; ?></span></li>

                    <li class="list-group-item li_flex"><b>Prénom</b><span><?php echo $_SESSION['membre']['prenom']; ?></span></li>

                    <li class="list-group-item li_flex"><b>Email</b><span><?php echo $_SESSION['membre']['email']; ?></span></li>

                    <li class="list-group-item li_flex"><b>Sexe</b><span><?php echo $civilite; ?></span></li>

                    <li class="list-group-item li_flex"><b>Statut</b><span><?php echo $statut; ?></span></li>
                    
                </ul>
            </div>


        </div>


    </main>



<?php 
include 'inc/footer.inc.php';

