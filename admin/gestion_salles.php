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
// SUPPRESSION DES SALLES
//--------------------------------------------------------------------------
if( isset($_GET['action']) && $_GET['action'] == 'supprimer' && !empty($_GET['id_salle']) ) {
    $suppression = $pdo->prepare("DELETE FROM salle WHERE id_salle = :id_salle");
    $suppression->bindParam(':id_salle', $_GET['id_salle'], PDO::PARAM_STR);
    $suppression->execute();
}


//--------------------------------------------------------------------------
// ENREGISTREMENT & MODIFICATION DES SALLES
//--------------------------------------------------------------------------

// Pour la modification
$id_salle = '';
$ancienne_photo = '';

$titre = '';
$description = '';
$photo = '';
$pays = '';
$ville = '';
$adresse = '';
$cp = '';
$capacite = '';
$categorie = '';



// Quand le formulaire est validé on vérifie tous les champs, sauf la Photo
if( isset($_POST['titre']) && isset($_POST['description']) && isset($_POST['pays']) && isset($_POST['ville']) && isset($_POST['adresse']) && isset($_POST['cp']) && isset($_POST['capacite']) && isset($_POST['categorie']) ) {

    $titre = trim($_POST['titre']);
    $description = trim($_POST['description']);
    $pays = trim($_POST['pays']);
    $ville = trim($_POST['ville']);
    $adresse = trim($_POST['adresse']);
    $cp = trim($_POST['cp']);
    $capacite = trim($_POST['capacite']);
    $categorie = trim($_POST['categorie']);

    

    // Pour la modif, récupération de l'id et de la photo
    if( !empty($_POST['id_salle']) ) {
        $id_salle = trim($_POST['id_salle']);
    }
    if( !empty($_POST['ancienne_photo']) ) {
        $photo = trim($_POST['ancienne_photo']);
    }


    // Variable de controle
    $erreur = false;


    // Controle du titre obligatoire
    if( empty($titre) ) {
        $msg .= '<div class="alert alert-danger mt-3">Le titre est obligatoire.</div>';

        // Cas d'erreur 
        $erreur = true;
    }

    
    // Contrôle sur la disponibilité du titre en BDD
    $verif_titre = $pdo->prepare("SELECT * FROM salle WHERE titre = :titre");
    $verif_titre->bindParam(':titre', $titre, PDO::PARAM_STR);
    $verif_titre->execute();


    // Contrôle sur la disponibilité du titre en BDD sauf pour une modification
    if( $verif_titre->rowCount() > 0 && empty($id_salle) ) {
        $msg .= '<div class="alert alert-danger mt-3">Le titre existe déjà.</div>';

        $erreur = true;
    }


    // Contrôle sur l'image
    if( !empty($_FILES['photo']['name']) ) {
    
        // On renomme la photo avec le titre de la salle
        $photo = $titre . '-' . $_FILES['photo']['name'];

        // On vérifie l'extension de l'image
        // On crée un tableau avec les extensions acceptées
        $tab_extension = array('jpg', 'jpeg', 'png', 'gif', 'webp');

        // On récupère l'extension du fichier en 3 étapes
        $extension = strrchr($photo, '.');
        $extension = substr($extension, 1);
        $extension = strtolower($extension);

        // Si le format d'image correspond :
        if( in_array($extension, $tab_extension) ) {

            // On enlève espace et caractères spéciaux
            $photo = preg_replace('/[^A-Za-z0-9.\-]/', '', $photo);

            // Si OK on copie l'image dans un dossier
            if($erreur == false) {
                // On conserve l'image
                copy($_FILES['photo']['tmp_name'], ROOT_PATH . PROJECT_PATH . 'assets/img_salles/' . $photo);

            }

        // Si le format est invalide
        } else {
            $msg .= '<div class="alert alert-danger mt-3">Le format de l\'image est invalide. Les formats acceptés sont : jpg / jpeg /  png / gif / webp.</div>';

            $erreur = true;
        }
    
    } // fin : La photo a été chargé



    // Si la capacité est vide on affecte 1 pour ne pas avoir d'erreur sql + message utilisateur, on ne bloque pas l'enregistrement
    if( !is_numeric($capacite) || $capacite == 0) {
        $capacite = 1;
        $msg .= '<div class="alert alert-warning mt-3">Vous n\'avez pas renseigné correctement la capacité, par défaut la valeur est réglé à 1.</div>';
    }

    // Controle sur le code postal
    if(iconv_strlen($cp) != 5 || !is_numeric($cp)) {
        $msg .= '<div class="alert alert-danger mt-3">Le code postal doit être numérique et de taille 5.</div>';
        // cas d'erreur 
        $erreur = true;
    }



    // On peut enregistrer dans la BDD
    if($erreur == false) {

        // Si on modifie une salle
        if( !empty($id_salle) ) {
            $enregistrement = $pdo->prepare("UPDATE salle SET titre = :titre, description = :description, photo = :photo, pays = :pays, ville = :ville, adresse = :adresse, cp = :cp, capacite = :capacite, categorie = :categorie WHERE id_salle = :id_salle");

            $enregistrement->bindParam(':id_salle', $id_salle, PDO::PARAM_STR);

        // Sinon on enregistre une nouvelle entrée
        } else {
            $enregistrement = $pdo->prepare("INSERT INTO salle (titre, description, photo, pays, ville, adresse, cp, capacite, categorie) VALUES (:titre, :description, :photo, :pays, :ville, :adresse, :cp, :capacite, :categorie)");
        }

        $enregistrement->bindParam(':titre', $titre, PDO::PARAM_STR);
        $enregistrement->bindParam(':description', $description, PDO::PARAM_STR);
        $enregistrement->bindParam(':photo', $photo, PDO::PARAM_STR);
        $enregistrement->bindParam(':pays', $pays, PDO::PARAM_STR);
        $enregistrement->bindParam(':ville', $ville, PDO::PARAM_STR);
        $enregistrement->bindParam(':adresse', $adresse, PDO::PARAM_STR);
        $enregistrement->bindParam(':cp', $cp, PDO::PARAM_STR);
        $enregistrement->bindParam(':capacite', $capacite, PDO::PARAM_STR);
        $enregistrement->bindParam(':categorie', $categorie, PDO::PARAM_STR);
        $enregistrement->execute();

        $msg .= '<div class="alert alert-success mt-3">Vous avez enregistré un nouvel article.</div>';

    }


    


} // fin des isset


// RECUPERATION DES INFOS DE LA SALLE A MODIFIER
//--------------------------------------------------------------------------
if( isset($_GET['action']) && $_GET['action'] == 'modifier' && !empty($_GET['id_salle']) ) {

    $recup_infos = $pdo->prepare("SELECT * FROM salle WHERE id_salle = :id_salle");
    $recup_infos->bindParam(':id_salle', $_GET['id_salle'], PDO::PARAM_STR);
    $recup_infos->execute();

    $infos_salle = $recup_infos->fetch(PDO::FETCH_ASSOC);

    $id_salle = $infos_salle['id_salle'];
    $titre = $infos_salle['titre'];
    $description = $infos_salle['description'];
    $pays = $infos_salle['pays'];
    $ville = $infos_salle['ville'];
    $adresse = $infos_salle['adresse'];
    $cp = $infos_salle['cp'];
    $capacite = $infos_salle['capacite'];
    $categorie = $infos_salle['categorie'];
    $ancienne_photo = $infos_salle['photo'];

}


// RECUPERATION DES SALLES EN BDD POUR AFFICHAGE
//--------------------------------------------------------------------------
$liste_salles = $pdo->query("SELECT * FROM salle ORDER BY id_salle");




// Les affichages dans la page commencent depuis la ligne suivante :
include '../inc/header.inc.php';
include '../inc/nav-back.inc.php';

?>



    <!-- HEADER -->
    <header class="container mt-5">
        <div class="pt-5 rounded col-12">
            <h1 class="mt-5">Gestion des salles</h1>
            <p class="lead">Gérer vos salles dans cet espace : Enregistrement | Modification | Suppression</p>
            <!-- variable destinée à afficher des messages utilisateur  -->
            <?php echo $msg;  ?>
        </div>
    </header>


    <!-- MAIN -->
    <main class="container main-back mt-5" id="main-back-salles">


        <div class="row">
            <!-- ENREGISTREMENT DES SALLES -->
            <div class="col-12 mx-auto mb-5">
                <form method="post" action="" enctype="multipart/form-data">

                    <input type="hidden" name="id_salle" value="<?php echo $id_salle; ?>">
                    <input type="hidden" name="ancienne_photo" value="<?php echo $ancienne_photo; ?>">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <input type="text" class="form-control" id="titre" name="titre" value="<?php echo $titre; ?>" placeholder="Titre *">
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-control" id="pays" name="pays" value="<?php echo $pays; ?>" placeholder="Pays">
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-control" id="adresse" name="adresse" value="<?php echo $adresse; ?>" placeholder="Adresse">
                            </div>
                            <div class="input-group mb-3">
                                <label for="categorie" class="input-group-text">Categorie</label>
                                <select class="form-control" id="categorie" name="categorie">
                                    <option value="reunion">Réunion</option>
                                    <option value="bureau" <?php if($categorie == 'bureau') { echo 'selected'; } ?> >Bureau</option>
                                    <option value="formation" <?php if($categorie == 'formation') { echo 'selected'; } ?> >Formation</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="input-group mb-3">
                                <label for="photo" class="input-group-text">Photo</label>
                                <input type="file" class="form-control" id="photo" name="photo">
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-control" id="ville" name="ville" value="<?php echo $ville; ?>" placeholder="Ville">
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-control" id="cp" name="cp" value="<?php echo $cp; ?>" placeholder="Code Postal">
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-control" id="capacite" name="capacite" value="<?php echo $capacite; ?>" placeholder="Capacité">
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="mb-3">
                                <textarea class="form-control" id="description" rows="6" name="description" placeholder="Description"><?php echo $description; ?></textarea>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="mb-3">                                
                                <input type="submit" class="btn btn-mycolor text-white w-100" id="enregistrement" name="enregistrement" value="Enregistrer">
                            </div>
                        </div>


                    </div>
                </form>
            </div>

        </div>

        <!-- AFFICHAGE DES SALLES -->
        <div class="row">
                <div class="col-12 mx-auto bloc-table">
                    <table class="table table-bordered bg-white">
                        <tr class="bg-perso-green text-white">
                            <th>Id salle</th>
                            <th>Titre</th>
                            <th>Description</th>
                            <th>Photo</th>
                            <th>Pays</th>
                            <th>Ville</th>
                            <th>Adresse</th>
                            <th>CP</th>
                            <th>Capacité</th>
                            <th>Catégorie</th>
                            <th>Actions</th>
                        </tr>

                        <?php 
                            while($ligne = $liste_salles->fetch(PDO::FETCH_ASSOC)) {
                                echo '<tr>';
                                echo '<td>' . $ligne['id_salle'] . '</td>';
                                echo '<td>' . $ligne['titre'] . '</td>';
                                // echo '<td>' . substr($ligne['description'], 0, 17) . ' <a class="link-back-salles" href="">...</a></td>';

                                echo '<td>' . substr($ligne['description'], 0, 17) . ' <a class="link-back-salles" href="' . URL .'index.php">...</a></td>';


                                // echo '<td>' . $ligne['description'] . '</td>';
                                echo '<td class="text-center"><img src="' . URL . 'assets/img_salles/' . $ligne['photo'] . '" width="80"></td>';
                                echo '<td>' . $ligne['pays'] . '</td>';
                                echo '<td>' . $ligne['ville'] . '</td>';
                                echo '<td>' . $ligne['adresse'] . '</td>';
                                echo '<td>' . $ligne['cp'] . '</td>';
                                echo '<td>' . $ligne['capacite'] . '</td>';
                                echo '<td>' . $ligne['categorie'] . '</td>';
                                echo '
                                <td class="text-center"><a href="?action=modifier&id_salle=' . $ligne['id_salle'] . '" class="btn-modif"><i class="fas fa-edit"></i></a>';
                                echo '<a href="?action=supprimer&id_salle=' . $ligne['id_salle'] . '" class="btn-supr confirm_delete" ><i class="far fa-trash-alt"></i></a></td>';

                                echo '</tr>';

                            }
                        ?>
                    </table>
                </div>
        </div>

        

        
    </main>



<?php 
include '../inc/footer.inc.php';

