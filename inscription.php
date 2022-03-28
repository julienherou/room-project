<?php
include 'inc/init.inc.php';
include 'inc/functions.inc.php';


// Restriction d'accès, si l'utilisateur est connecté, on le renvoie vers profil.php
if(user_is_connected()) {
    header('location:profil.php'); // on set pour l'instant sur inscription (à modifier)
}


// Vérification de l'existance des informations provenants du formulaire

// On déclare des variables vides nous permettant de les appeler dans les values de nos champs du form. Si le form est validé, on récupère dans le if les valeurs dans ces variables, cela permettra d'afficher la valeur par défaut dans le form.
$pseudo = '';
$mdp = '';
$nom = '';
$prenom = '';
$email = '';
$civilite = '';

// Enregistrement :
if( isset($_POST['pseudo']) && isset($_POST['mdp']) && isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['email']) && isset($_POST['civilite']) ) {

    $pseudo = trim($_POST['pseudo']);
    $mdp = trim($_POST['mdp']);
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $civilite = trim($_POST['civilite']);


    // DEBUT DES CONTROLES

    // Variable de controle
    $erreur = false;


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
    if($verif_pseudo->rowCount() > 0) {
        $msg .= '<div class="alert alert-danger mt-3">Le pseudo est indisponible.</div>';

        $erreur = true;
    }


    
    // MDP
    // On s'assure que le mdp n'est pas vide
    if(empty($mdp)) {
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

    // CIVILITE
    if( $civilite == 'vide') {
        $msg .= '<div class="alert alert-danger mt-3">La civilité est obligatoire.</div>';

        $erreur = true;
    }



    // Si il n'y a pas eu d'erreur on peut lancer l'enregistrement sur la BDD

    if($erreur == false) {


        // On crypte le mdp
        $mdp = password_hash($mdp, PASSWORD_DEFAULT);
        
        // Pour le statut
        // 1 => membre
        // 2 => admin
        // ...

        // Commande d'enregistrement dans la BDD
        $inscription = $pdo->prepare("INSERT INTO membre (pseudo, mdp, nom, prenom, email, civilite, statut, date_enregistrement) VALUES (:pseudo, :mdp, :nom, :prenom, :email, :civilite, 1, NOW())");

        $inscription->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
        $inscription->bindParam(':mdp', $mdp, PDO::PARAM_STR);
        $inscription->bindParam(':nom', $nom, PDO::PARAM_STR);
        $inscription->bindParam(':prenom', $prenom, PDO::PARAM_STR);
        $inscription->bindParam(':email', $email, PDO::PARAM_STR);
        $inscription->bindParam(':civilite', $civilite, PDO::PARAM_STR);

        $inscription->execute();

        $msg .= '<div class="alert alert-success mt-3">Félicitation! Vous êtes maintenant inscrit.</div>';
    }

} // Fin isset





// Les affichages dans la page commencent depuis la ligne suivante :
include 'inc/header.inc.php';
include 'inc/nav-front.inc.php';


?>




    <!-- HEADER -->
    <header class="container mt-5 header-inscription">
        <div class="pt-5 rounded col-lg-8 mx-auto">
            <h1 class="my-4">Inscription</h1>
            <p>Veuillez compléter tous les champs<br>
                du formulaire pour valider l'inscription</p>
            <!-- variable destinée à afficher des messages utilisateur  -->
            <?php echo $msg;  ?>
        </div>
    </header>



    <!-- MAIN -->
    <main class="container" id="main-inscription">


        <div class="row">

            <div class="col-lg-8 mt-5 mx-auto">

                <form method="post" enctype="multipart/form-data">

                    <div class="row">
                        <div class="col-lg-6 p-2">
                            <input type="text" class="form-control" id="pseudo" name="pseudo" value="<?php echo $pseudo; ?>" placeholder="Pseudo*">
                        </div>
                        <div class="col-lg-6 p-2">
                            <input type="text" class="form-control" id="mdp" name="mdp" placeholder="Mot de passe*">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 p-2">
                            <input type="text" class="form-control" id="nom" name="nom" value="<?php echo $nom; ?>" placeholder="Nom*">
                        </div>
                        <div class="col-lg-6 p-2">
                            <input type="text" class="form-control" id="prenom" name="prenom" value="<?php echo $prenom; ?>" placeholder="Prenom*">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 p-2">
                            <input type="text" class="form-control" id="email" name="email" value="<?php echo $email; ?>" placeholder="Email*">
                        </div>
                        <div class="col-lg-6 p-2">
                            <select class="form-control" id="civilite" name="civilite">
                                <option value="vide" >Civilité*</option>
                                <option value="m" <?php if($civilite == 'm') { echo 'selected'; } ?>>Homme</option>
                                <option value="f" <?php if($civilite == 'f') { echo 'selected'; } ?>>Femme</option>
                                <option value="nb" <?php if($civilite == 'nb') { echo 'selected'; } ?>>Non binaire</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 p-2">                              
                            <input type="submit" class="btn btn-mycolor text-white w-100" id="envoyer" name="envoyer" value="Envoyer">
                        </div>
                    </div>

                </form>

            </div>
        </div>



    </main>



<?php 
include 'inc/footer.inc.php';

