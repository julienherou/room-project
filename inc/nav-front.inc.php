<body id="page-top" class="body-front">    
    
    <!-- NAVIGATION FRONT -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top nav-front" id="mainNav">
    <!-- <nav class="navbar navbar-expand-lg navbar-dark nav-front" id="mainNav"> -->
        <div class="container-fluid">

            <div class="content-logo">
                <a class="navbar-brand" href="<?php echo URL; ?>index.php">
                MyRoom<span>&reg;</span>
                </a>
            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">

                <ul class="navbar-nav ms-auto mb-2 mb-md-0">

                    <!-- SI L'UTILISATEUR EST ADMIN -->
                    <?php if( user_is_admin() ) { ?>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdown-admin" data-bs-toggle="dropdown" aria-expanded="false"><span class="link-fx">Administration</span></a>
                        <ul class="dropdown-menu " aria-labelledby="dropdown-admin">
                            <li><a class="dropdown-item" href="<?php echo URL; ?>admin/gestion_salles.php">Gestion des salles</a></li>
                            <li><a class="dropdown-item" href="<?php echo URL; ?>admin/gestion_produits.php">Gestion des produits</a></li>
                            <li><a class="dropdown-item" href="<?php echo URL; ?>admin/gestion_membres.php">Gestion des membres</a></li>
                            <li><a class="dropdown-item" href="<?php echo URL; ?>admin/gestion_avis.php">Gestion des avis</a></li>
                            <li><a class="dropdown-item" href="<?php echo URL; ?>admin/gestion_commandes.php">Gestion des commandes</a></li>
                            <li><a class="dropdown-item" href="<?php echo URL; ?>admin/statistiques.php">Statistiques</a></li>
                        </ul>
                    </li>

                    <?php } ?>

                    <li class="nav-item">
                        <a class="nav-link <?php echo class_active('/index.php'); ?>" aria-current="page" href="<?php echo URL; ?>index.php"><span class="link-fx">Trouver une salle</span></a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link <?php echo class_active('/contact.php'); ?>" href="<?php echo URL; ?>contact.php"><span class="link-fx">Contactez-nous</span></a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdown-user" data-bs-toggle="dropdown" aria-expanded="false"><span class="link-fx">Espace client</span></a>
                        <ul class="dropdown-menu" aria-labelledby="dropdown-user">

                            <!-- SI L'UTILISATEUR EST CONNECTE -->
                            <?php if( user_is_connected() ) { ?>
                                
                            <li class="nav-item">
                                <a class="dropdown-item <?php echo class_active('/profil.php'); ?>" href="<?php echo URL; ?>profil.php">Profil</a>
                            </li>
                            <li class="nav-item">
                                <a class="dropdown-item <?php echo class_active('/connexion.php'); ?>" href="<?php echo URL; ?>connexion.php?action=deconnexion">Déconnexion</a>
                            </li>

                            <!-- SI L'UTILISATEUR N'EST PAS CONNECTE -->
                            <?php } else { ?>

                            <li class="nav-item">
                                <a class="dropdown-item <?php echo class_active('/inscription.php'); ?>" href="<?php echo URL; ?>inscription.php">Inscription</a>
                            </li>
                            <li class="nav-item">
                                <a class="dropdown-item <?php echo class_active('/connexion.php'); ?>" href="<?php echo URL; ?>connexion.php">Connexion</a>
                            </li>

                            <?php } ?>
                                
                        </ul>
                    </li>


                </ul>

            </div>

        </div>
    </nav>