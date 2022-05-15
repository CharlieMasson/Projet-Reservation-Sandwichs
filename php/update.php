<?php
date_default_timezone_set('Europe/Paris');
require ('connexion.php');

function ConnectB($DB)
{
    return mysqli_connect("localhost","root","root",$DB);
}

$dbName = ConnectB("reservesandwich");

if(isset($_GET['idCommandeForm'])){
    try
    {
        $idCommandeForm = $_GET['idCommandeForm'];
        $commandeForm = $bdd->query("SELECT * FROM commande WHERE id_com='$idCommandeForm'");
        $commandeForm->execute();
        $result = $commandeForm->fetchAll();
        echo json_encode($result);
    }catch(PDOExeption $e){
        die("Erreur lors de l'obtention des données");
    }
    
}

if(isset($_POST['idCommandeUpdate'])){
    try
    {
        $idCommandeUpdate = $_POST['idCommandeUpdate'];
        $sandwichSelected = $_POST['sandwichSelected'];
        $boissonSelected = $_POST['boissonSelected'];
        $dessertSelected = $_POST['dessertSelected'];
        $chipsSelected = $_POST['chipsSelected'];
        $dateSelected = $_POST['dateSelected'];
        $commandeToUpdate = $bdd->query("UPDATE commande SET fk_sandwich_id='$sandwichSelected', fk_boisson_id='$boissonSelected', fk_dessert_id='$dessertSelected', chips_com='$chipsSelected', date_heure_livraison_com='$dateSelected' WHERE id_com='$idCommandeUpdate'");
        $commandeToUpdate->execute();
    }catch(PDOExeption $e){
        die("Erreur lors de l'obtention des données");
    }
}

?>

