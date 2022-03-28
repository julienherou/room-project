<?php

// Connexion à la BDD : room
$host = 'mysql:host=localhost;dbname=room';
$login = 'root';
$password = '';
$options = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
);
$pdo = new PDO($host, $login, $password, $options);


// On ouvre une session :
session_start();


// Déclaration des constantes (define)
// Constante représentant l'url absolue racine de notre projet room
define('URL', 'http://localhost/DIW60/site_web_dynamique/room/'); // ⚠ à modifier lors de la mise en ligne

// Constante représentant le chemin racine serveur pour l'enregistrement des images depuis gestion_salles.php
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']); // C:/wamp64/www // info récupérée dans la superglobale donc il ne sera pas necessaire de la changer


// Constante représentant le chemin depuis notre serveur vers le dossier de notre projet
define('PROJECT_PATH', '/DIW60/site_web_dynamique/room/'); // depuis notre dossier www, vers la racine de notre projet. Attention de ne pas oublier le premier /


// Variable destinée à afficher des messages utilisateurs.
$msg = '';
