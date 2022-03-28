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
// SUPPRESSION DES MEMBBRES
//--------------------------------------------------------------------------
if( isset($_GET['action']) && $_GET['action'] == 'supprimer' && !empty($_GET['id_membre']) ) {
    $suppression = $pdo->prepare("DELETE FROM membre WHERE id_membre = :id_membre");
    $suppression->bindParam(':id_membre', $_GET['id_membre'], PDO::PARAM_STR);
    $suppression->execute();
}


//--------------------------------------------------------------------------
// ENREGISTREMENT & MODIFICATION DES MEMBRES
//--------------------------------------------------------------------------

$id_membre = '';
// $date_enregistrement = '';

$pseudo = '';
$mdp = '';
$nom = '';
$prenom = '';
$email = '';
$civilite = '';
$statut = '';



// Le formulaire est validé >> on vérifie tous les champs
if( isset($_POST['pseudo']) && isset($_POST['mdp']) && isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['email']) && isset($_POST['civilite']) && isset($_POST['statut']) ) {

    $pseudo = trim($_POST['pseudo']);
    $mdp = trim($_POST['mdp']);
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $civilite = trim($_POST['civilite']);
    $statut = trim($_POST['statut']);


    // Pour la modif, récupération de l'id membre
    // if( !empty($_POST['id_membre']) ) {
    //     $id_membre = trim($_POST['id_membre']);
    // }
    
    if( !empty($_GET['id_membre']) ) {
        $id_membre = trim($_GET['id_membre']);
    }



    // Variable de controle
    $erreur = false;

    if( !empty($id_membre) ) {
        echo 'on est dans une modif' . '<br>';
    }
    echo 'test';

    // PSEUDO
    // Taille du pseudo : entre 4 et 14 caractères (inclus)
    if( iconv_strlen($pseudo) < 4 || iconv_strlen($pseudo) > 14 ) {
        $msg .= '<div class="alert alert-danger mt-3">Le pseudo doit avoir entre 4 et 14 caractères inclus.</div>';

        // Si cas d'erreur
        $erreur = true;
    }
    // Filtre des caractères autorisés
    $verif_caractere = preg_match('#^[a-zA-Z0-9._-]+$#', $pseudo);

    if(!$verif_caractere) {
        $msg .= '<div class="alert alert-danger mt-3">Les caractères autorisés pour le pseudo sont : A-Z 0-9 _ . -</div>';

        $erreur = true;
    }
    // Vérification de l'unicité du pseudo
    $verif_pseudo = $pdo->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
    $verif_pseudo->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
    $verif_pseudo->execute();

    if($verif_pseudo->rowCount() > 0 && empty($id_membre) ) {
        $msg .= '<div class="alert alert-danger mt-3">Le pseudo est indisponible.</div>';

        $erreur = true;
    }



    // MDP
    // On s'assure que le mdp n'est pas vide
    if(empty($mdp) && empty($id_membre)) {
        $msg .= '<div class="alert alert-danger mt-3">Le mot de passe est obligatoire.</div>';
        $erreur = true;
    }

    

    // NOM
    if( empty($nom) ) {
        $msg .= '<div class="alert alert-danger mt-3">Le nom est obligatoire.</div>';
        $erreur = true;
    }
    
    // PRENOM
    if( empty($prenom) ) {
        $msg .= '<div class="alert alert-danger mt-3">Le prenom est obligatoire.</div>';
        $erreur = true;
    }

    // MAIL
    // On s'assure que le mail est valide
    if( filter_var($email, FILTER_VALIDATE_EMAIL) == false ){
        $msg .= '<div class="alert alert-danger mt-3">Le format du mail est incorrect.</div>';

        $erreur = true;
    }




    // ON PEUT ENREGISTRER DANS LA BDD
    if($erreur == false) {

        // On crypte le mdp
        $mdp = password_hash($mdp, PASSWORD_DEFAULT);

        // Si on modifie un membre
        if( !empty($id_membre) ) {
            $enregistrement = $pdo->prepare("UPDATE membre SET nom = :nom, prenom = :prenom, email = :email, civilite = :civilite, statut = :statut WHERE id_membre = :id_membre");
            $enregistrement->bindParam(':id_membre', $id_membre, PDO::PARAM_STR);
            $enregistrement->bindParam(':nom', $nom, PDO::PARAM_STR);
            $enregistrement->bindParam(':prenom', $prenom, PDO::PARAM_STR);
            $enregistrement->bindParam(':email', $email, PDO::PARAM_STR);
            $enregistrement->bindParam(':civilite', $civilite, PDO::PARAM_STR);
            $enregistrement->bindParam(':statut', $statut, PDO::PARAM_STR);
            $enregistrement->execute();
    
            $msg .= '<div class="alert alert-success mt-3">Vous avez modifié un membre.</div>';

        } else {
            $enregistrement = $pdo->prepare("INSERT INTO membre (pseudo, mdp, nom, prenom, email, civilite, statut, date_enregistrement) VALUES (:pseudo, :mdp, :nom, :prenom, :email, :civilite, :statut, NOW())");
            $enregistrement->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
            $enregistrement->bindParam(':mdp', $mdp, PDO::PARAM_STR);
            $enregistrement->bindParam(':nom', $nom, PDO::PARAM_STR);
            $enregistrement->bindParam(':prenom', $prenom, PDO::PARAM_STR);
            $enregistrement->bindParam(':email', $email, PDO::PARAM_STR);
            $enregistrement->bindParam(':civilite', $civilite, PDO::PARAM_STR);
            $enregistrement->bindParam(':statut', $statut, PDO::PARAM_STR);
            $enregistrement->execute();
    
            $msg .= '<div class="alert alert-success mt-3">Vous avez enregistré un nouveau membre.</div>';
        }


    }



} // Fin des isset


// RECUPERATION DES INFOS DE PRODUITS A MODIFIER
//--------------------------------------------------------------------------
if( isset($_GET['action']) && $_GET['action'] == 'modifier' && !empty($_GET['id_membre']) ) {

    $recup_infos = $pdo->prepare("SELECT * FROM membre WHERE id_membre = :id_membre");
    $recup_infos->bindParam(':id_membre', $_GET['id_membre'], PDO::PARAM_STR);
    $recup_infos->execute();

    $infos_membre = $recup_infos->fetch(PDO::FETCH_ASSOC);

    $pseudo = $infos_membre['pseudo'];
    // $mdp = $infos_membre['mdp'];
    $email = $infos_membre['email'];
    $civilite = $infos_membre['civilite'];
    $nom = $infos_membre['nom'];
    $prenom = $infos_membre['prenom'];
    $statut = $infos_membre['statut'];


}


// echo $pseudo . '<br>';
// echo $email . '<br>';
// echo $nom . '<br>';
// echo $prenom . '<br>';
// echo $civilite . '<br>';
// echo $statut . '<br>';

// RECUPERATION DES PRODUITS EN BDD POUR AFFICHAGE
//--------------------------------------------------------------------------
$liste_membres = $pdo->query("SELECT * FROM membre ORDER BY id_membre");





// Les affichages dans la page commencent depuis la ligne suivante :
include '../inc/header.inc.php';
include '../inc/nav-back.inc.php';

?>



    <!-- HEADER -->
    <header class="container mt-5 header-membre">
        <div class="pt-5 rounded col-12">
            <h1 class="mt-5">Gestion des membres</h1>
            <p class="lead">Gérer les informations de vos membres dans cet espace.</p>
            
            <!-- variable destinée à afficher des messages utilisateur  -->
            <?php echo $msg;  ?>
        </div>
    </header>


    <!-- MAIN -->
    <main class="container main-back mt-5" id="main-back-produits">

        <!-- ENREGISTREMENT DES MEMBRES -->
        <div class="row">
        <a href="<?php echo URL; ?>admin/gestion_membres.php" class="notes mb-4">Créer un nouveau membre.</a>

            <div class="col-12 mx-auto mb-5">
                <form method="post" enctype="multipart/form-data">

                    <!-- variable cachée pour la modification -->
                    <input type="hidden" name="id_membre" value="<?php echo $id_membre; ?>">
                    <div class="row">

                        <div class="col-sm-6">
                            <div class="mb-3 input-group">
                                <span class="input-group-text"><i class="fas fa-user couleur_icone"></i></span>
                                <input type="text" class="form-control" id="pseudo" name="pseudo" value="<?php echo $pseudo; ?>" placeholder="Pseudo">
                            </div>
                            <div class="mb-3 input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="text" class="form-control" id="email" name="email" value="<?php echo $email; ?>" placeholder="Email">
                            </div>
                            <div class="mb-3 input-group">
                                <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                <input type="text" class="form-control" id="nom" name="nom" value="<?php echo $nom; ?>" placeholder="Nom">
                            </div>
                            <div class="mb-3 input-group">
                                <span class="input-group-text"><i class="fas fa-user-tag couleur_icone"></i></span>
                                <select class="form-control" id="statut" name="statut">
                                    <option value="1">Membre</option>
                                    <option value="2" <?php if($statut == '2') { echo 'selected'; } ?>>Admin</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="mb-3 input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" id="mdp" name="mdp" placeholder="Mot de passe">
                            </div>
                            <div class="mb-3 input-group">
                                <span class="input-group-text"><i class="fas fa-venus-mars couleur_icone"></i></span>
                                <select class="form-control" id="civilite" name="civilite">
                                    <option value="m">Homme</option>
                                    <option value="f" <?php if($civilite == 'f') { echo 'selected'; } ?>>Femme</option>
                                    <option value="nb" <?php if($civilite == 'nb') { echo 'selected'; } ?>>Non binaire</option>
                                </select>
                            </div>
                            <div class="mb-3 input-group">
                                <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                <input type="text" class="form-control" id="prenom" name="prenom" value="<?php echo $prenom; ?>" placeholder="Prenom">
                            </div>
                            <div class="mb-3">                                
                                <input type="submit" class="btn btn-mycolor text-white w-100" id="enregistrement" name="enregistrement" value="Enregistrer">
                            </div>
                        </div>

                    </div>

                </form>
            </div>

        </div>

        <!-- AFFICHAGE DES MEMBRES -->
        <div class="row">
            <div class="col-12 mx-auto bloc-table">
                <table class="table table-bordered bg-white">
                    <tr class="bg-perso-green text-white">
                        <th>Id membre</th>
                        <th>Pseudo</th>
                        <th>Nom</th>
                        <th>Prenom</th>
                        <th>Email</th>
                        <th>Civilité</th>
                        <th>Statut</th>
                        <th>Date d'enregistrement</th>
                        <th>Action</th>
                    </tr>

                    <?php
                        while($ligne = $liste_membres->fetch(PDO::FETCH_ASSOC)) {
                            echo '<tr>';
                            echo '<td>' . $ligne['id_membre'] . '</td>';
                            echo '<td>' . $ligne['pseudo'] . '</td>';
                            echo '<td>' . $ligne['nom'] . '</td>';
                            echo '<td>' . $ligne['prenom'] . '</td>';
                            echo '<td>' . $ligne['email'] . '</td>';
                            echo '<td>';
                            if ($ligne['civilite'] == 'm' ){
                                echo 'Homme';
                            } elseif($ligne['civilite'] == 'f'){
                                echo 'Femme';
                            } else {
                                echo 'Non binaire';
                            };
                            echo '</td>';
                            echo '<td>';
                            if ($ligne['statut'] == 2){
                                echo 'Admin';
                            } else {
                                echo 'Membre';
                            };
                            echo '</td>';
                            echo '<td>' . date('d/m/Y à H:i', strtotime($ligne['date_enregistrement'])) . '</td>';
                            echo '
                            <td class="text-center"><a href="?action=modifier&id_membre=' . $ligne['id_membre'] . '" class="btn-modif"><i class="fas fa-edit"></i></a>';
                            echo '<a href="?action=supprimer&id_membre=' . $ligne['id_membre'] . '" class="btn-supr confirm_delete" ><i class="far fa-trash-alt"></i></a></td>';
                            echo '</tr>';
                        }
                    ?>
                </table>
            </div>
        </div>
        
    </main>



<?php 
include '../inc/footer.inc.php';

