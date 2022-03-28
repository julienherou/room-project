<?php
include 'inc/init.inc.php';
include 'inc/functions.inc.php';


// REQUETES BDD

// Affichage du nombre de salle par catégories 
$liste_categories = $pdo->query(" SELECT categorie, COUNT(*) AS nombre FROM salle, produit WHERE salle.id_salle = produit.id_salle GROUP BY categorie ");

// Affichage du nombre total de salles
$total_salles = $pdo->query(" SELECT COUNT(*) AS nombre FROM salle, produit WHERE salle.id_salle = produit.id_salle ");
$donnees_total_salles = $total_salles->fetch(PDO::FETCH_ASSOC);

// Affichage du nombre de salle par villes
$liste_villes = $pdo->query(" SELECT ville, COUNT(*) AS nombre FROM salle, produit WHERE salle.id_salle = produit.id_salle GROUP BY ville ");

// Affichage du nombre de salle par capacités
$liste_capacites = $pdo->query(" SELECT capacite, COUNT(*) AS nombre FROM salle, produit WHERE salle.id_salle = produit.id_salle GROUP BY capacite ");

// On déclare des variables pour le filtre par date
$filtre_arrivee = '';
$filtre_depart = '';




// Isset sur les salles
if( isset($_GET['categorie']) ) {
    $select_salles = $pdo->prepare("SELECT * FROM salle, produit WHERE categorie = :categorie AND salle.id_salle = produit.id_salle ORDER BY categorie, titre");
    $select_salles->bindParam(':categorie', $_GET['categorie'], PDO::PARAM_STR);
    $select_salles->execute();

} elseif( isset($_GET['ville']) ) {
    $select_salles = $pdo->prepare("SELECT * FROM salle, produit WHERE ville = :ville AND salle.id_salle = produit.id_salle ORDER BY ville, titre");
    $select_salles->bindParam(':ville', $_GET['ville'], PDO::PARAM_STR);
    $select_salles->execute();

} elseif( isset($_GET['capacite']) ) {
    $select_salles = $pdo->prepare("SELECT * FROM salle, produit WHERE capacite = :capacite AND salle.id_salle = produit.id_salle ORDER BY capacite, titre");
    $select_salles->bindParam(':capacite', $_GET['capacite'], PDO::PARAM_STR);
    $select_salles->execute();

} elseif( isset($_GET['prix_cat1']) ) {
    $select_salles = $pdo->query("SELECT * FROM salle, produit WHERE salle.id_salle = produit.id_salle AND prix < 500 ORDER BY prix, titre ");

} elseif( isset($_GET['prix_cat2']) ) {
    $select_salles = $pdo->query("SELECT * FROM salle, produit WHERE salle.id_salle = produit.id_salle AND prix >= 500 AND prix < 1000 ORDER BY prix, titre ");

} elseif( isset($_GET['prix_cat3']) ) {
    $select_salles = $pdo->query("SELECT * FROM salle, produit WHERE salle.id_salle = produit.id_salle AND prix >= 1000 ORDER BY prix, titre ");

} elseif ( isset($_POST['filtre_arrivee']) && isset($_POST['filtre_depart']) ) {
    $filtre_arrivee = trim($_POST['filtre_arrivee']);
    $filtre_depart = trim($_POST['filtre_depart']);
    // On change le format de date pour la requete en bdd
    $filtre_arrivee = date('Y-m-d H:i:s ', strtotime($filtre_arrivee));
    $filtre_depart = date('Y-m-d H:i:s ', strtotime($filtre_depart));

    // echo $filtre_arrivee;
    // echo '<br>';
    // echo $filtre_depart;


    // Requete en prepare pour filtrer les dates
    $select_salles = $pdo->prepare(" SELECT * FROM salle, produit WHERE salle.id_salle = produit.id_salle AND date_arrivee >= :date_arrivee AND date_depart <= :date_depart ORDER BY date_arrivee, titre ");
    $select_salles->bindParam(':date_arrivee', $filtre_arrivee, PDO::PARAM_STR);
    $select_salles->bindParam(':date_depart', $filtre_depart, PDO::PARAM_STR);
    $select_salles->execute();

} else {
    $select_salles = $pdo->query("SELECT * FROM salle, produit WHERE salle.id_salle = produit.id_salle ORDER BY categorie, titre");
}




// Les affichages dans la page commencent depuis la ligne suivante :
include 'inc/header.inc.php';
include 'inc/nav-front.inc.php';

?>


    <!-- HEADER -->
    <header class="header-shop">
        <div class="bloc-menu-shop">

            <!-- NAVBAR FILTRE DU BAS -->
            <nav class="navbar navbar-expand-lg navbar-light nav-front nav-bottom">
                <div class="container-fluid">
                    <!-- <div class="collapse navbar-collapse" id="navbarSupportedContent2"> -->
                    <div class="collapse navbar-collapse contain-nav-bottom" id="navbarSupportedContent2">
                        <!-- <ul class="navbar-nav me-auto mb-2 mb-md-0 contain-nav-bottom"> -->
                        <ul class="navbar-nav me-auto mb-2 mb-md-0 nav-bottom-left">
                            <!-- FILTRE PAR CATEGORIE -->
                            <!-- <div class="nav-bottom-left"> -->

                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo URL; ?>index.php"><span class="link-fx">Toutes les salles</span><?php echo '<sup class="s-number"> ' . $donnees_total_salles['nombre'] . '</sup>'; ?></a>
                            </li>

                            <?php

                            while($ligne = $liste_categories->fetch(PDO::FETCH_ASSOC)) {
                                echo '<li class="nav-item">';
                                echo '<a class="nav-link" href="?categorie=' . $ligne['categorie'] . ' ">';
                                echo '<span class="link-fx">' . ucfirst($ligne['categorie']) . '</span>';
                                echo '<sup class="s-number"> ' . $ligne['nombre'] . '</sup>';
                                echo '</a>';
                                echo '</li>';
                            }

                            // while($ligne = $liste_categories->fetch(PDO::FETCH_ASSOC)) {
                            //     echo '<li class="nav-item">';
                            //     echo '<a class="nav-link ' . class_active('/index.php') . '?categorie=' . $ligne['categorie'] . '" href="?categorie=' . $ligne['categorie'] . ' ">';
                            //     echo '<span class="link-fx">' . ucfirst($ligne['categorie']) . '</span>';
                            //     echo '<sup class="s-number"> ' . $ligne['nombre'] . '</sup>';
                            //     echo '</a>';
                            //     echo '</li>';
                            // }

                            ?>   

                        </ul>
                        <ul class="navbar-nav mb-2 mb-md-0 nav-bottom-right">
                            <!-- <div class="nav-bottom-right"> -->
                            <!-- FILTRE PAR VILLE -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="dropdown-ville" data-bs-toggle="dropdown" aria-expanded="false"><span class="link-fx">Par ville</span></a>
                                <ul class="dropdown-menu " aria-labelledby="dropdown-ville">

                                    <?php

                                    while($ligne = $liste_villes->fetch(PDO::FETCH_ASSOC)) {

                                        echo '<li>';
                                        echo '<a class="dropdown-item" href="?ville=' . $ligne['ville'] . '">' . ucfirst($ligne['ville']) . ' <sup class="s-number"> ' . $ligne['nombre'] . '</sup></a>';
                                        echo '</li>';

                                    }

                                    ?>  

                                </ul>
                            </li>
                            <!-- FILTRE PAR CAPACITE -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="dropdown-capacite" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="link-fx">Par capacité</span>
                                </a>
                                <ul class="dropdown-menu " aria-labelledby="dropdown-capacite">

                                    <?php

                                    while($ligne = $liste_capacites->fetch(PDO::FETCH_ASSOC)) {

                                        echo '<li>';
                                        echo '<a class="dropdown-item" href="?capacite=' . $ligne['capacite'] . '">' . ucfirst($ligne['capacite']) . ' personnes</a>';
                                        echo '</li>';

                                    }

                                    ?>  

                                </ul>

                            </li>
                            <!-- FILTRE PAR PRIX -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="dropdown-prix" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="link-fx">Par prix</span>
                                </a>
                                <ul class="dropdown-menu dropdown-prix" aria-labelledby="dropdown-prix">
                                    <li><a class="dropdown-item" href="?prix_cat1">Moins de 500 €</a></li>
                                    <li><a class="dropdown-item" href="?prix_cat2">De 500 à 1000 €</a></li>
                                    <li><a class="dropdown-item" href="?prix_cat3">Plus de 1000 €</a></li>
                                </ul>
                            </li>
                            <!-- FILTRE PAR DATE -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="link-fx">Par date</span>
                                </a>
                                
                                <div class="dropdown-menu dropdown-date p-3" aria-labelledby="dropdownMenuLink">
                                    <form method="post" action="<?php echo URL; ?>index.php" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label for="filtre_arrivee" class="form-label">Date d'arrivée</label>
                                                <input type="text" class="form-control" id="filtre_arrivee" name="filtre_arrivee" autocomplete="off">
                                            </div>
                                            <div class="col-sm-6">
                                                <label for="filtre_depart" class="form-label">Date de départ</label>
                                                <input type="text" class="form-control" id="filtre_depart" name="filtre_depart" autocomplete="off">
                                            </div>
                                            <div class="col-sm-12 mt-3">                              
                                                <input type="submit" class="btn text-white w-100" id="enregistrement" name="enregistrement" value="Filtrer">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                
                            </li>

                        </ul>
                    </div>
                </div>
            </nav>

            <!-- GROS TITRE ET QUANTITE AFFICHE -->
            <div class="title-shop">
                <?php
                if( isset($_GET['categorie']) ) {
                    $choix_selection = $pdo->prepare("SELECT categorie, COUNT(*) AS nombre FROM salle, produit WHERE categorie = :categorie AND salle.id_salle = produit.id_salle ORDER BY categorie, titre");
                    $choix_selection->bindParam(':categorie', $_GET['categorie'], PDO::PARAM_STR);
                    $choix_selection->execute();
                    $choix = $choix_selection->fetch(PDO::FETCH_ASSOC);
                    echo '<h1>' . $choix['categorie'] . '<sup class="b-number">' . $choix['nombre'] . '</sup></h1>';
                } elseif( isset($_GET['ville']) ) {
                    $choix_selection = $pdo->prepare("SELECT ville, COUNT(*) AS nombre FROM salle, produit WHERE ville = :ville AND salle.id_salle = produit.id_salle ORDER BY ville, titre");
                    $choix_selection->bindParam(':ville', $_GET['ville'], PDO::PARAM_STR);
                    $choix_selection->execute();
                    $choix = $choix_selection->fetch(PDO::FETCH_ASSOC);
                    echo '<h1>' . $choix['ville'] . '<sup class="b-number">' . $choix['nombre'] . '</sup></h1>';
                } elseif( isset($_GET['capacite']) ) {
                    $choix_selection = $pdo->prepare("SELECT COUNT(*) AS nombre FROM salle, produit WHERE capacite = :capacite AND salle.id_salle = produit.id_salle ORDER BY capacite, titre");
                    $choix_selection->bindParam(':capacite', $_GET['capacite'], PDO::PARAM_STR);
                    $choix_selection->execute();
                    $choix = $choix_selection->fetch(PDO::FETCH_ASSOC);
                    echo '<h1>Nos salles <sup class="b-number"> ' . $choix['nombre'] . '</sup></h1>';
                } elseif( isset($_GET['prix_cat1']) ) {
                    $choix_selection = $pdo->query("SELECT COUNT(*) AS nombre FROM salle, produit WHERE salle.id_salle = produit.id_salle AND prix < 500 ORDER BY prix, titre ");
                    $choix = $choix_selection->fetch(PDO::FETCH_ASSOC);
                    echo '<h1>Nos salles <sup class="b-number"> ' . $choix['nombre'] . '</sup></h1>';
                } elseif( isset($_GET['prix_cat2']) ) {
                    $choix_selection = $pdo->query("SELECT COUNT(*) AS nombre FROM salle, produit WHERE salle.id_salle = produit.id_salle AND prix >= 500 AND prix < 1000 ORDER BY prix, titre ");
                    $choix = $choix_selection->fetch(PDO::FETCH_ASSOC);
                    echo '<h1>Nos salles <sup class="b-number"> ' . $choix['nombre'] . '</sup></h1>';
                } elseif( isset($_GET['prix_cat3']) ) {
                    $choix_selection = $pdo->query("SELECT COUNT(*) AS nombre FROM salle, produit WHERE salle.id_salle = produit.id_salle AND prix >= 1000 ORDER BY prix, titre ");
                    $choix = $choix_selection->fetch(PDO::FETCH_ASSOC);
                    echo '<h1>Nos salles <sup class="b-number"> ' . $choix['nombre'] . '</sup></h1>';
                } elseif ( isset($_POST['filtre_arrivee']) && isset($_POST['filtre_depart']) ) {
                    $filtre_arrivee = trim($_POST['filtre_arrivee']);
                    $filtre_depart = trim($_POST['filtre_depart']);
                    $filtre_arrivee = date('Y-m-d H:i:s ', strtotime($filtre_arrivee));
                    $filtre_depart = date('Y-m-d H:i:s ', strtotime($filtre_depart));

                    $choix_selection = $pdo->prepare(" SELECT COUNT(*) AS nombre FROM salle, produit WHERE salle.id_salle = produit.id_salle AND date_arrivee >= :date_arrivee AND date_depart <= :date_depart ");
                    $choix_selection->bindParam(':date_arrivee', $filtre_arrivee, PDO::PARAM_STR);
                    $choix_selection->bindParam(':date_depart', $filtre_depart, PDO::PARAM_STR);
                    $choix_selection->execute();
                    $choix = $choix_selection->fetch(PDO::FETCH_ASSOC);
                    echo '<h1>Nos salles <sup class="b-number"> ' . $choix['nombre'] . '</sup></h1>';
                } else{
                    echo '<h1>Nos salles <sup class="b-number"> ' . $donnees_total_salles['nombre'] . '</sup></h1>';
                }
                ?>
            </div>

        </div>
    </header>


    <!-- MAIN -->
    <main class="container-fluid" id="main-shop">

        <!-- AFFICHAGE DES FICHES SALLES -->
        <div class="row mt-5">
            <?php

            while ($salle = $select_salles->fetch(PDO::FETCH_ASSOC)) {

                echo '
                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="contain-img">
                        <img src="' . URL . 'assets/img_salles/' . $salle['photo'] . '" class="card-img-top" alt="Une image de la salle ' . $salle['titre'] . '">
                            <a href="fiche_produit.php?id_produit=' . $salle['id_produit'] . '" class="d-grid gap-2 col-4 mx-auto contain-btn">                            
                                <div class="btn btn-primary">Voir plus</div>
                            </a>
                        </div>
                        <!-- <div class="card-body mt-2"> -->
                        <div class="card-body p-2">
                            <div class="card-text-top">
                                <p class="card-text"><strong class="me-1">Salle ' . ucfirst($salle['titre']) . ' </strong><span>' . $salle['capacite'] . ' personnes</span></p>
                                <p class="card-text">' . $salle['prix'] . ' € <i class="fas fa-circle"></i></p>
                            </div>
                            <div class="card-text-bottom">
                                <p class="card-text">' . date('d/m/Y', strtotime($salle['date_arrivee'])) . '</p>
                                <div class="card-arrow"><span class="card-line"></span><i class="fas fa-long-arrow-alt-right"></i></div>
                                <p class="card-text">' . date('d/m/Y', strtotime($salle['date_depart'])) . '</p>
                            </div>
                        </div>
                    </div>
                </div>';

            }


            ?>   


        </div>



        <!-- Lien à remplacer dans l'echo produit-->
        <!-- <a class="portfolio-link" data-bs-toggle="modal" href="#img-salle-modal"><img src="' . URL . 'assets/img_salles/' . $salle['photo'] . '" class="card-img-top" alt="Une image de la salle ' . $salle['titre'] . '"></a> -->


        <!-- <div class="portfolio-modal modal fade" id="img-salle-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="close-modal" data-bs-dismiss="modal"><img src="assets/img/close-icon.svg" alt="Close modal" /></div>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-8">
                                <div class="modal-body">
                                    <h2 class="text-uppercase">Project Name</h2>
                                    <p class="item-intro text-muted">Lorem ipsum dolor sit amet consectetur.</p>
                                    <?php
                                    // echo '
                                    // <img src="' . URL . 'assets/img_salles/' . $salle['photo'] . '" class="card-img-top" alt="Une image de la salle ' . $salle['titre'] . '">';
                                    ?>
                                    <p>Use this area to describe your project. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Est blanditiis dolorem culpa incidunt minus dignissimos deserunt repellat aperiam quasi sunt officia expedita beatae cupiditate, maiores repudiandae, nostrum, reiciendis facere nemo!</p>
                                    <ul class="list-inline">
                                        <li>
                                            <strong>Client:</strong>
                                            Explore
                                        </li>
                                        <li>
                                            <strong>Category:</strong>
                                            Graphic Design
                                        </li>
                                    </ul>
                                    <button class="btn btn-primary btn-xl text-uppercase" data-bs-dismiss="modal" type="button">
                                        <i class="fas fa-times me-1"></i>
                                        Close Project
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->







    </main>



<?php 
include 'inc/footer.inc.php';

