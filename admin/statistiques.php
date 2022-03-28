<?php
include '../inc/init.inc.php';
include '../inc/functions.inc.php';



// Restriction d'accès, si l'utilisateur n'est pas admin, on le redirige vers connexion.php
if( !user_is_admin() ) {
    header('location:../connexion.php');
    exit();
}




// REQUETES BDD POUR STATISTIQUES
//--------------------------------------------------------------------------

// Top 5 des salles les mieux notées
$recup_salle_note = $pdo->query("SELECT titre, ROUND(AVG(note), 1) AS nombre FROM avis, salle WHERE avis.id_salle = salle.id_salle GROUP BY avis.id_salle ORDER BY AVG(note) DESC LIMIT 0, 5;");


// Top 5 des salles les plus commandées
$recup_salle_commande = $pdo->query("SELECT titre, COUNT(titre) AS nombre FROM commande, produit, salle WHERE commande.id_produit = produit.id_produit AND produit.id_salle = salle.id_salle GROUP BY titre ORDER BY COUNT(titre) DESC LIMIT 0, 5;");


// Top 5 des membres qui achètent le plus (en termes de quantité).
$recup_membre_commande = $pdo->query("SELECT pseudo, email, COUNT(commande.id_membre) AS nombre FROM commande, membre WHERE commande.id_membre = membre.id_membre GROUP BY commande.id_membre ORDER BY COUNT(commande.id_membre) DESC LIMIT 0, 5;");


// Top 5 des membres qui achètent le plus cher (en termes de prix)
$recup_membre_prix = $pdo->query("SELECT pseudo, email, prix FROM produit, membre, commande WHERE commande.id_produit = produit.id_produit AND commande.id_membre = membre.id_membre GROUP BY prix ORDER BY prix DESC LIMIT 0, 5;");




// Les affichages dans la page commencent depuis la ligne suivante :
include '../inc/header.inc.php';
include '../inc/nav-back.inc.php';

?>


    <!-- HEADER -->
    <header class="container mt-5 header-back-stat">
        <div class="pt-5 rounded col-12">
            <h2 class="mt-5">Statistiques</h1>
            <!-- <p class="lead">Observer ici l'évolution de votre site.</p> -->
            <!-- variable destinée à afficher des messages utilisateur  -->
            <?php echo $msg;  ?>
        </div>
    </header>

    <!-- MAIN -->
    <main class="container main-back mt-5" id="main-back-stat">


        <div class="row">
            <!-- Salles les mieux notées -->
            <div class="col-lg-6 mt-2 mb-5 mx-auto list-stat p-5">
                <h3 class="ms-3 mb-5">Salles les mieux notées</h3>
                <ol class="list-group list-group-numbered list-group-flush">
                    <?php
                    while($ligne = $recup_salle_note->fetch(PDO::FETCH_ASSOC)) {
                        echo '<li class="list-group-item d-flex justify-content-between"><span class=" ms-2 me-auto">';
                        echo $ligne['titre'] . '</span>';
                        echo '<span class="badge bg-primary rounded-pill">';
                        // stars($ligne['nombre']) . '</span>';
                        echo $ligne['nombre'] . '</span>';
                        echo '</li>';
                    }
                    ?>
                </ol>
            </div>

            <div class="col-lg-5 mt-2 mb-5 mx-auto list-stat p-5">
                <h3 class="ms-3 mb-5">Salles les plus commandées</h3>
                <ol class="list-group list-group-numbered list-group-flush">
                    <?php
                    while($ligne = $recup_salle_commande->fetch(PDO::FETCH_ASSOC)) {
                        echo '<li class="list-group-item d-flex justify-content-between"><span class=" ms-2 me-auto">';
                        echo $ligne['titre'] . '</span>';
                        echo '<span class="badge bg-primary rounded-pill">';
                        echo $ligne['nombre'] . '</span>';
                        echo '</li>';
                    }
                    ?>
                </ol>
            </div>

        </div>


        <div class="row">

            <div class="col-lg-5 mt-2 mb-5 mx-auto list-stat p-5">
                <h3 class="ms-3 mb-5">Membres qui commandent le plus</h3>
                <ol class="list-group list-group-numbered list-group-flush">
                    <?php
                    while($ligne = $recup_membre_commande->fetch(PDO::FETCH_ASSOC)) {
                        echo '<li class="list-group-item d-flex justify-content-between align-items-start">';
                        echo '<div class="ms-2 me-auto"><div>' . $ligne['pseudo'] . '</div>' . $ligne['email'] . '</div>';
                        echo '<span class="badge bg-primary rounded-pill">';
                        echo $ligne['nombre'] . '</span>';
                        echo '</li>';
                    }
                    ?>
                </ol>
            </div>

            <div class="col-lg-6 mt-2 mb-5 mx-auto list-stat p-5">
                <h3 class="ms-3 mb-5">Membres qui commandent le plus cher</h3>
                <ol class="list-group list-group-numbered list-group-flush">
                    <?php
                    while($ligne = $recup_membre_prix->fetch(PDO::FETCH_ASSOC)) {
                        echo '<li class="list-group-item d-flex justify-content-between align-items-start">';
                        echo '<div class="ms-2 me-auto"><div>' . $ligne['pseudo'] . '</div>' . $ligne['email'] . '</div>';
                        echo '<span class="badge bg-primary rounded-pill">';
                        echo $ligne['prix'] . ' €' . '</span>';
                        echo '</li>';
                    }
                    ?>
                </ol>
            </div>

        </div>




    </main>



<?php 
include '../inc/footer.inc.php';
