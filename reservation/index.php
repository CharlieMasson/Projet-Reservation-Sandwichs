<!-- /* ------------------------------------------
PHP
 --------------------------------------------- */ -->

 <?php

	date_default_timezone_set('Europe/Paris');
	require '../php/script_login.php';
	require '../php/auth.php';

	if(!est_connecte()){ 
		header('Location: ../login.php'); //si l'utilisateur n'est pas connecté il est redirigé vers login.php
		exit();
	}

	if(time() - $_SESSION['timestamp'] > 3600) { //si plus d'une heure est écoulé
		unset($_SESSION['timestamp']);
		$_SESSION['connecte'] = false;
		$_SESSION['admin'] = false;
		$_SESSION['expired'] = true;
		$_SESSION['id_utilisateur'] = null;
		header("Location: ../login.php"); //redirige vers la page login
		exit;
	}

	if (isset($_SESSION["id_utilisateur"])){
		$idUtilisateur = $_SESSION['id_utilisateur'];
	}

	if(ISSET($_SESSION['admin'])){
		if ($_SESSION['admin'] == true){
			header("Location:error.php");
		}
	}

 $sandwich = $boisson = $dessert = $chips = $dateCommande = "";

 if(isset($_POST["submit-btn"]))
 {



	$boisson = $_POST["boisson"];
	$sandwich = $_POST["sandwich"];
	$dessert = $_POST["dessert"];
	$chips = $_POST["chips"];
	$dateLivraison = $_POST["dateLivraison"];
	$datetime = explode("T",$dateLivraison); //explode utilisé pour récupéré la date de dateLivraison sans l'heure
	$jourLivraison = $datetime[0];
	$heure = date('H:i');
	$jour = date('Y-m-d');

	$isSuccess = true;

	if (date("D",strtotime($dateLivraison))== 'Sat' || date("D",strtotime($dateLivraison)) == 'Sun'){ //test pour savoir si la réservation a été faite pour dimanche ou samedi
		$erreur = "Erreur: Vous ne pouvez pas réserver pour un Samedi ou un Dimanche";
		$isSuccess = false;
	}

	if ($jourLivraison == $jour){ //test pour savoir si la réservation a été fait après 9h30 pour le jour même
		if($heure > '9:30'){
			$erreur = "Erreur: Vous ne pouvez pas réserver pour après 9h30 pour le jour même";
			$isSuccess = false;
		}
	}

	if ($jourLivraison < $jour){
		$erreur = "Erreur: Vous ne pouvez pas réserver dans le passé!";
		$isSuccess = false;
	}

	if ($isSuccess == true){
		$db = connexion();
		$requete = $db->prepare("INSERT INTO commande(fk_user_id, fk_sandwich_id, fk_boisson_id, fk_dessert_id, chips_com, date_heure_livraison_com, annule_com) VALUES(?,?,?,?,?,?,?)");
		$requete -> execute(array($idUtilisateur, $sandwich, $boisson, $dessert, $chips, $dateLivraison, 0));
		$db = deconnexion();
		$reserve = true;
	}
	else{
		$reserve = false;
	}
 }
 
?>


<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Cantine</title>

	<!-- Google font -->
	<link href="https://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet">

	<!-- Bootstrap -->
	<link type="text/css" rel="stylesheet" href="../css/bootstrap.min.css" />

	<!-- Custom stlylesheet -->
	<link type="text/css" rel="stylesheet" href="../css/reservation.css" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
</head>

<body>
	<div id="formulaire" class="section">
		<div class="section-center">
			<div class="container">
				<div class="row">
					<div class="booking-form">
						<div class="form-header">
							<h1>Choisissez votre formule:</h1>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<form method="post" action="index.php">
										<label for="date_comm">Choisis le jour et l'heure de ta commande : </label>
										<input class="form-group" type="datetime-local" id="dateLivraison" name="dateLivraison">
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<select name="sandwich"class="form-control" required>
														<option value="" selected hidden>Sandwich</option>
														<?php 
															$db = connexion();
															$requete=$db->query("SELECT * FROM sandwich");
															while($sandwich = $requete->fetch()){
																if ($sandwich['dispo_sandwich'] == 1){
																	echo '<option value="' . $sandwich['id_sandwich'] . '"> ' . $sandwich['nom_sandwich'] . '</option>';
																}
															}
															$db = deconnexion();
														?>
													</select>
													<span class="select-arrow"></span>
													<span class="form-label">Sandwich</span>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<select name="boisson"class="form-control" required>
														<option value="" selected hidden>Boisson</option>
														<?php 
															$db = connexion();
															$requete=$db->query("SELECT * FROM boisson");
															while($boisson = $requete->fetch()){
																if ($boisson['dispo_boisson'] == 1){
																	echo '<option value="' . $boisson['id_boisson'] . '"> ' . $boisson['nom_boisson'] . '</option>';
																}
															}
															$db = deconnexion();
														?>
													</select>
													<span class="select-arrow"></span>
													<span class="form-label">Boisson</span>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<select name="dessert" class="form-control" required>
														<option value="" selected hidden>dessert</option>
														<?php 
															$db = connexion();
															$requete=$db->query("SELECT * FROM dessert");
															while($dessert = $requete->fetch()){
																if ($dessert['dispo_dessert'] == 1){
																	echo '<option value="' . $dessert['id_dessert'] . '"> ' . $dessert['nom_dessert'] . '</option>';
																}
															}
															$db = deconnexion();
														?>
													</select>
													<span class="select-arrow"></span>
													<span class="form-label">Dessert</span>
												</div>
											</div>
										</div>
										<div class="box-btns">
											<h4>Voulez-vous des chips?</h4>
											<div class="group">
												<input name="chips"type="radio" value="1"/>
												<label for="Oui" class="rond">Oui</label>
												<input name="chips" type="radio" value="0"/>
												<label for="Non" class="rond">Non</label>												
											</div>
										</div>
										<div class="erreur">
											<?php if (ISSET($erreur)){
												echo $erreur;
											}
											?>
										</div>
										<div class="form-btn">
											<button type="submit" class="submit-btn" name="submit-btn">Réserver</button>
										</div>
										<div class="output">
											<?php
												if (ISSET($reserve)){
													if ($reserve == true){
														echo"La commande a bien été passée!";
													}
												}
											?>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>

</html>