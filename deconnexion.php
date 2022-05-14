<?php 
session_start();
unset($_SESSION['timestamp']);
$_SESSION['connecte'] = 0; //termine la session
$_SESSION['admin'] = false;
header("Location: ../index.php"); //redirige vers la page de connexion
exit;
?>