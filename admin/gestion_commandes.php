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
    exit();
}



// SUPPRESSION DES COMMANDES
//--------------------------------------------------------------------------
if( isset($_GET['action']) && $_GET['action'] == 'supprimer' && !empty($_GET['id_commande']) ) {
    $suppression = $pdo->prepare("DELETE FROM commande WHERE id_commande = :id_commande");
    $suppression->bindParam(':id_commande', $_GET['id_commande'], PDO::PARAM_STR);
    $suppression->execute();
}



// RECUPERATION DES COMMANDES POUR AFFICHAGE
//--------------------------------------------------------------------------
$liste_commande = $pdo->query("SELECT * FROM salle, produit, membre, commande WHERE salle.id_salle = produit.id_salle AND membre.id_membre = commande.id_membre AND produit.id_produit = commande.id_produit ORDER BY id_commande");



// Les affichages dans la page commencent depuis la ligne suivante :
include '../inc/header.inc.php';
include '../inc/nav-back.inc.php';

?>



    <!-- HEADER -->
    <header class="container mt-5">
        <div class="pt-5 rounded col-12">
            <h1 class="mt-5">Gestion des commandes</h1>
            <p class="lead">Gérer les commandes dans cet espace.</p>
            <!-- variable destinée à afficher des messages utilisateur  -->
            <?php echo $msg;  ?>
        </div>
    </header>

    <!-- MAIN -->
    <main class="container main-back mt-5" id="main-back-commande">

        <!-- AFFICHAGE DES AVIS -->
        <div class="row">
            <div class="col-12 mx-auto bloc-table">
                <table class="table table-bordered bg-white">
                    <tr class="bg-perso-green text-white">
                        <th>Id commande</th>
                        <th>Id membre</th>
                        <th>Id salle</th>
                        <th>Réservation</th>
                        <th>Prix</th>
                        <th>Date d'enregistrement</th>
                        <th>Etat</th>
                        <th>Action</th>
                    </tr>

                    <?php
                    while($ligne = $liste_commande->fetch(PDO::FETCH_ASSOC)) {
                        echo '<tr>';
                        echo '<td>' . $ligne['id_commande'] . '</td>';
                        echo '<td>N° ' . $ligne['id_membre'] . ' - ' . $ligne['pseudo'] . ' - ' . $ligne['email'] . '</td>';
                        echo '<td>N° ' . $ligne['id_salle'] . ' - Salle ' . $ligne['titre'];
                        echo '<td>' . date('d/m/Y', strtotime($ligne['date_arrivee'])) . ' au ' . date('d/m/Y', strtotime($ligne['date_depart'])) . '</td>';
                        echo '<td>' . $ligne['prix'] . '&nbsp;€</td>';
                        echo '<td> ' . date('d/m/Y à H:i', strtotime($ligne['date_enregistrement'])) . '</td>';
                        if ( $ligne['etat'] == 'reservation') {
                            echo '<td>réservé</td>';
                        } else {
                            echo '<td>' . $ligne['etat'] . '</td>';
                        }
                        echo '
                            <td class="text-center">
                                <a href="?action=supprimer&id_commande=' . $ligne['id_commande'] . '" class="btn-supr confirm_delete" ><i class="far fa-trash-alt"></i></a>
                            </td>';
                        echo '</tr>';
                    }
                    ?>
                </table>
            </div>
        </div>

    </main>



<?php 
include '../inc/footer.inc.php';
