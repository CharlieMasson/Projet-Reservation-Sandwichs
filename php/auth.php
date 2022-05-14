<?php
#fonction pour savoir si un utilisateur est connecté
function est_connecte(): bool {
    if(session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return !empty($_SESSION['connecte']);
}

#fonction pour forcer un utilisateur non connecté à ce connecter 
function forcer_utilisateur_connecte(){
    if(est_connecte()){
        header('Location: login.php');
        return void;
    }
}

?>