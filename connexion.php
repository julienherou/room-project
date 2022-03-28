<?php
include 'inc/init.inc.php';
include 'inc/functions.inc.php';




// Gestion de la déconnexion de l'utilisateur
if( isset($_GET['action']) && $_GET['action'] == 'deconnexion' ) {
    session_destroy(); // On détruit la session
}


// Si l'utilisateur est connecté, on le renvoie vers profil.php
if(user_is_connected()) {
    header('location:profil.php');
}


$pseudo = '';
$mdp = '';

// Si le formulaire est validé :
if( isset($_POST['pseudo']) && isset($_POST['mdp']) ) {

    $pseudo = trim($_POST['pseudo']);
    $mdp = trim($_POST['mdp']);

    // On récupère les infos du pseudo dans la BDD
    $connexion = $pdo->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
    $connexion->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
    $connexion->execute();

    // Si le pseudo est trouvé dans la BDD :
    if($connexion->rowCount() > 0) {

        // On vérifie le mdp
        $infos = $connexion->fetch(PDO::FETCH_ASSOC);

        // Si le mdp correspond :
        if(password_verify($mdp, $infos['mdp'])) {

            // On place dans la $_SESSION les informations utilisateur (sauf le mdp) dans un soustableau "membre"
            $_SESSION['membre'] = array();
            $_SESSION['membre']['id_membre'] = $infos['id_membre'];
            $_SESSION['membre']['pseudo'] = $infos['pseudo'];
            $_SESSION['membre']['nom'] = $infos['nom'];
            $_SESSION['membre']['prenom'] = $infos['prenom'];
            $_SESSION['membre']['email'] = $infos['email'];
            $_SESSION['membre']['civilite'] = $infos['civilite'];
            $_SESSION['membre']['statut'] = $infos['statut'];
            $_SESSION['membre']['date_enregistrement'] = $infos['date_enregistrement'];

            // On redirige l'utilisateur vers la page profil.php
            header('location:profil.php');

        // Si le mdp ne correspond pas :
        } else {
            $msg .= '<div class="alert alert-danger mt-3">Vous avez une erreur sur votre mot de passe.</div>';
        }

    // Si le pseudo n'est pas trouvé dans la BDD
    } else {
        $msg .= '<div class="alert alert-danger mt-3">Vous avez une erreur sur votre pseudo.</div>';
    }


}




// Les affichages dans la page commencent depuis la ligne suivante :
include 'inc/header.inc.php';
include 'inc/nav-front.inc.php';


?>


    <!-- HEADER -->

    <header class="container header-connexion">
        <div class="pt-5 col-lg-7 mx-auto">
            <h1 class="my-5">Mon compte</h1>
        </div>
    </header>


    <!-- MAIN -->
    <main class="container" id="main-connexion">


        <div class="border-connexion col-lg-7 mx-auto p-4">

            <div class="col-lg-10 mx-auto mt-4">
                <h2 class="mb-4">Se connecter</h2>
                <!-- variable destinée à afficher des messages utilisateur  -->
                <?php echo $msg;  ?>
            </div>

            <!-- <div class="row"> -->

            <div class="col-lg-10 mx-auto mb-4">

                <form method="post" enctype="multipart/form-data">

                    <div class="row">
                        <div class="col-lg-12 p-2">
                            <label for="pseudo" class="form-label">Votre pseudo*</label>                            
                            <input type="text" class="form-control" id="pseudo" name="pseudo" value="<?php echo $pseudo; ?>" placeholder="Votre pseudo">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 p-2">
                            <label for="mdp" class="form-label">Mot de passe*</label>
                            <input type="text" class="form-control" id="mdp" name="mdp" placeholder="Mot de passe">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 p-2">                              
                            <input type="submit" class="btn btn-mycolor text-white w-100" id="connexion" name="connexion" value="Connexion">
                        </div>
                    </div>

                </form>

            </div>

            <!-- </div> -->

        </div>


    </main>



<?php 
include 'inc/footer.inc.php';

