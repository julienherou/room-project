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



// SUPPRESSION DES AVIS
//--------------------------------------------------------------------------
if( isset($_GET['action']) && $_GET['action'] == 'supprimer' && !empty($_GET['id_avis']) ) {
    $suppression = $pdo->prepare("DELETE FROM avis WHERE id_avis = :id_avis");
    $suppression->bindParam(':id_avis', $_GET['id_avis'], PDO::PARAM_STR);
    $suppression->execute();
}



// RECUPERATION DES AVIS POUR AFFICHAGE
//--------------------------------------------------------------------------
$liste_avis = $pdo->query("SELECT * FROM salle, membre, avis WHERE salle.id_salle = avis.id_salle AND membre.id_membre = avis.id_membre");



// Les affichages dans la page commencent depuis la ligne suivante :
include '../inc/header.inc.php';
include '../inc/nav-back.inc.php';

?>



    <!-- HEADER -->
    <header class="container mt-5">
        <div class="pt-5 rounded col-12">
            <h1 class="mt-5">Gestion des avis</h1>
            <p class="lead">Gérer les commentaires de vos membres dans cet espace.</p>
            <!-- variable destinée à afficher des messages utilisateur  -->
            <?php echo $msg;  ?>
        </div>
    </header>

    <!-- MAIN -->
    <main class="container main-back mt-5" id="main-back-avis">

        <!-- AFFICHAGE DES AVIS -->
        <div class="row">
            <div class="col-12 mx-auto bloc-table">
                <table class="table table-bordered bg-white">
                    <tr class="bg-perso-green text-white">
                        <th>Id avis</th>
                        <th>Membre</th>
                        <th>Salle</th>
                        <th>Commentaire</th>
                        <th>Note</th>
                        <th>Date d'enregistrement</th>
                        <th>Action</th>
                    </tr>

                    <?php
                    while($ligne = $liste_avis->fetch(PDO::FETCH_ASSOC)) {
                        echo '<tr>';
                        echo '<td>' . $ligne['id_avis'] . '</td>';
                        echo '<td>N° ' . $ligne['id_membre'] . ' - ' . $ligne['pseudo'] . ' (' . $ligne['email'] . ')</td>';
                        echo '<td>N° ' . $ligne['id_salle'] . ' - Salle ' . $ligne['titre'] . '</td>';
                        echo '<td>' . $ligne['commentaire'] . '</td>';
                        echo '<td class="td-sansretour">';
                        if( $ligne['note'] == 1 ) {
                            echo '<i class="fas fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>';
                        } elseif( $ligne['note'] == 2 ){
                            echo '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>';
                        } elseif( $ligne['note'] == 3 ){
                            echo '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>';
                        } elseif( $ligne['note'] == 4 ){
                            echo '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i>';
                        } elseif( $ligne['note'] == 5 ){
                            echo '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>';
                        }
                        echo '</td>';
                        echo '<td>' . date('d/m/Y à H:i', strtotime($ligne['date_enregistrement'])) . '</td>';
                        echo '
                            <td class="text-center">
                                <a href="?action=supprimer&id_avis=' . $ligne['id_avis'] . '" class="btn-supr confirm_delete" ><i class="far fa-trash-alt"></i></a>
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
