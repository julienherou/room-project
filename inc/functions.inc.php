<?php

// Fonction pour mettre la classe active sur les liens de navigation
function class_active($url) {
    $page = strrchr($_SERVER['PHP_SELF'], '/');
    if($page == $url) {
        return ' active ';
    }
}


// Fonction permettant de savoir si l'utilisateur est connecté : true / false
function user_is_connected() {
    if( !empty($_SESSION['membre']) ) {
        return true;
    } else {
        return false;
    }
}


// Fonction permettant de savoir si un utilisateur est connecté et si son statut est admin
function user_is_admin(){
    if(user_is_connected() && $_SESSION['membre']['statut'] == 2) {
        return true;
    }
    return false;
}


// Fonction permettant d'afficher des étoiles comme notation
function stars($note){
    if( $note >= 1 && $note < 1.5 ) {
        echo '<i class="fas fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>';
    } elseif( $note >= 1.5 && $note < 2 ){
        echo '<i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>';
    } elseif( $note >= 2 && $note < 2.5 ){
        echo '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>';
    } elseif( $note >= 2.5 && $note < 3 ){
        echo '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i><i class="far fa-star"></i><i class="far fa-star"></i>';
    } elseif( $note >= 3 && $note < 3.5 ){
        echo '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>';
    } elseif( $note >= 3.5 && $note < 4 ){
        echo '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i><i class="far fa-star"></i>';
    } elseif( $note >= 4 && $note < 4.5 ){
        echo '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i>';
    } elseif( $note >= 4.5 && $note < 5 ){
        echo '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>';
    } elseif( $note >= 5 ){
        echo '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>';
    } else {
        echo '<i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>';
    }
}



