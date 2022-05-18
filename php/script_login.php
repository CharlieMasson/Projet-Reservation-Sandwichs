<?php 
    #fonction pour se connecter et se deconnecter de la bdd
    function connexion(){
        require 'logindetails.php';
        try{
        $pdo = new PDO('mysql:host=' . $dbhost . ';dbname=' . $dbname . ';charset=UTF8', $dbuser, $dbpswd, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        return $pdo;
        }
        catch(PDOexception $e){
            die("Une erreur est survenue lors de la connexion à la base de données" . $e);
        }
    }
    function deconnexion(){
        $pdo = null;
        return $pdo;
    }
?>