<?php
	// connexion

	require "logindetails.php";

	try
	{    
		$bdd = new PDO("mysql:host=$dbHost;dbname=$dbName; charset=utf8", $dbUser, $dbPswd);
		// mode de fetch :   FETCH_OBJ    
		$bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
		$bdd->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}catch(PDOException $e) 
	{
		echo("Err BDAcc01Erreur : erreur de connexion dans dans la base de données<br>Message d'erreur :" . $e->getMessage());
	}
?>