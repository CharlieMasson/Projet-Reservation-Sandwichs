<?php
	// connexion

	$dbHost = "localhost";

	$dbName= "reservesandwich";
    
	$dbUser = "root";

	$dbUserPassword = "root";
	
	$connection = null;

	try
	{    
		$bdd = new PDO("mysql:host=$dbHost;dbname=$dbName; charset=utf8", $dbUser, $dbUserPassword);
		// mode de fetch :   FETCH_OBJ    
		$bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
		$bdd->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}catch(PDOException $e) 
	{
		echo("Err BDAcc01Erreur : erreur de connexion dans dans la base de données<br>Message d'erreur :" . $e->getMessage());
	}
?>