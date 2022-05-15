<?php
date_default_timezone_set('Europe/Paris');
require ('connexion.php');

function ConnectB($DB)
{
    return mysqli_connect("localhost","root","root",$DB);
}

$dbName = ConnectB("reservesandwich");
    if(!empty($_POST['min']) AND !empty($_POST['max']) AND !empty($_POST['utilisateur'])){
        
        $min = $_POST['min'];
        $max = $_POST['max'];
        $filtreUserId = $_POST['utilisateur'];
        
        

        $insererFiltre = $bdd->prepare('INSERT INTO historique(dateDebut_hist, dateFin_hist, fk_user_id)VALUES(?, ?, ?)');
        $insererFiltre->execute(array($min, $max, $filtreUserId));
        echo "Le filtre à bien été créer";
    }else{
        echo "Veuillez choisir des dates valides";
    }

    if(isset($_POST['idFiltreDelete'])){
        try
        {
            $idFiltreDelete = $_POST['idFiltreDelete'];
            $delete = $bdd->query("DELETE FROM historique WHERE id_hist=$idFiltreDelete");  
        }catch(PDOExeption $e){
            die("Erreur lors de l'obtention des données");
        }
    }
?>

