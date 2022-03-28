<?php
include 'inc/init.inc.php';
include 'inc/functions.inc.php';



// On déclare des variables vides nous permettant de les appeler dans les values de nos champs du form. Si le form est validé, on récupère dans le if les valeurs dans ces variables, cela permettra d'afficher la valeur par défaut dans le form.
$prenom = '';
$nom = '';
$email = '';
$objet = '';
$message = '';

// echo '<pre>'; print_r($_POST); echo '</pre>';

// Enregistrement :
if( isset($_POST['prenom']) && isset($_POST['nom']) && isset($_POST['email']) && isset($_POST['objet']) && isset($_POST['message']) ) {

    $prenom = trim($_POST['prenom']);
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $objet = trim($_POST['objet']);
    $message = trim($_POST['message']);


    // DEBUT DES CONTROLES

    // Variable de controle
    $erreur = false;

    // PRENOM
    if( empty($prenom) ) {
        $msg .= '<div class="alert alert-danger mt-3">Le prenom est obligatoire.</div>';
        $erreur = true;
    }

    // NOM
    if( empty($nom) ) {
        $msg .= '<div class="alert alert-danger mt-3">Le nom est obligatoire.</div>';
        $erreur = true;
    }
    


    // MAIL
    // On s'assure que le mail est valide
    if( filter_var($email, FILTER_VALIDATE_EMAIL) == false ){
        $msg .= '<div class="alert alert-danger mt-3">Le format du mail est incorrect.</div>';

        $erreur = true;
    }

    if( empty($objet) ) {
        $msg .= '<div class="alert alert-danger mt-3">L\'objet est obligatoire.</div>';
        $erreur = true;
    }

    if( empty($message) ) {
        $msg .= '<div class="alert alert-danger mt-3">Le message est obligatoire.</div>';
        $erreur = true;
    }



    // Si il n'y a pas eu d'erreur on peut envoyer le mail

    if($erreur == false) {

        // On rajoute le texte : 'From: ' devant l'expéditeur pour être mieux accepté par les serveurs mail (pour éviter de passer dans les spams)
        $expediteur = 'From: ' . $prenom . ' ' . $nom;

        // Fonction prédéfinie mail()
        // En commentaire car fait planter la BDD
        // mail($email, $objet, $message, $expediteur);   

        $msg .= '<div class="alert alert-success mt-3">Nous avons bien reçu votre demande, nous vous répondrons dans les plus brefs délais.</div>';
    }

} // Fin isset




// Les affichages dans la page commencent depuis la ligne suivante :
include 'inc/header.inc.php';
include 'inc/nav-front.inc.php';


?>



    <!-- HEADER -->
    <header class="container mt-5 header-contact">
        <div class="pt-5 rounded col-lg-8 mb-3">
            <h2>Pour toutes questions<br>
                vous pouvez nous contacter<br>
                en remplissant ce formulaire.</h1>
            <!-- variable destinée à afficher des messages utilisateur  -->
            <?php echo $msg;  ?>
        </div>
    </header>



    <!-- MAIN -->
    <main class="container" id="main-contact">
        <div class="row">

            <div class="col-lg-8 mt-5">

                <form method="post">
                    <div class="row">
                        <div class="col-lg-6 p-2">
                            <input type="text" class="form-control" id="prenom" name="prenom" value="<?php echo $prenom; ?>" placeholder="Prenom*">
                        </div>
                        <div class="col-lg-6 p-2">
                            <input type="text" class="form-control" id="nom" name="nom" value="<?php echo $nom; ?>" placeholder="Nom*">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 p-2">
                            <input type="text" class="form-control" id="email" name="email" value="<?php echo $email; ?>" placeholder="Email*">
                        </div>
                        <div class="col-lg-6 p-2">
                            <input type="text" class="form-control" id="objet" name="objet" value="<?php echo $objet; ?>" placeholder="Objet*">
                        </div>
                    </div>
                    <div class="row">
                    <div class="col-lg-12 p-2">
                            <textarea class="form-control" id="message" rows="6" name="message" placeholder="Message*"><?php echo $message; ?></textarea>
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

