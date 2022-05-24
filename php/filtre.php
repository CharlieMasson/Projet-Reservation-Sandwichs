<?php
date_default_timezone_set('Europe/Paris');
require ('script_login_historique.php');


function ConnectB($DB)
{
    return mysqli_connect("localhost","root","root",$DB);
}



$dbName = ConnectB("reservesandwich");
    if(!empty($_POST['min']) AND !empty($_POST['max'])){
       try
       {
        $min = $_POST['min'];
        $max = $_POST['max'];
        $idUtilisateur = $_POST['idUtilisateur'];

        $insererFiltre = $bdd->prepare('INSERT INTO historique(dateDebut_hist, dateFin_hist, fk_user_id)VALUES(?, ?, ?)');
        $insererFiltre->execute(array($min, $max, $idUtilisateur));
        echo "Le filtre à bien été créer";
       }catch(PDOExeption $e){
        die("Erreur lors de l'obtention des données");
       }
    }
    

    if(isset($_POST['idFiltreDelete'])){
        try
        {
            $idFiltreDelete = $_POST['idFiltreDelete'];
            $delete = $bdd->query("DELETE FROM historique WHERE id_hist=$idFiltreDelete");  
            $delete->execute();
        }catch(PDOExeption $e){
            die("Erreur lors de l'obtention des données");
        }
    }
?>

