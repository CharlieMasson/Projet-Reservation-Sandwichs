<?php
require '../php/script_login.php';
require '../php/auth.php';
session_start();
if(!($_SESSION['admin'] == false) && est_connecte()){
    echo("Erreur: Seul les comptes élèves ont accès à cette fonction.");
    echo("<a class='btn btn-secondary' href='../index.php'> Retour sur la page d'acceuil </a>");
    exit();
}
else{
    header("index.php");
}
?>

<!DOCTYPE html>
<html lang="fr">
<meta charset="utf-8"/>
    <head>
        <!-- Bootstrap, fontawesome, Jquerry, css -->
        <meta charset="utf-8"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="../css/backoffice.css">
    </head>
</html>