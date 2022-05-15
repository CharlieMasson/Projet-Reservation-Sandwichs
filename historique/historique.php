<?php
date_default_timezone_set('Europe/Paris');
require ('../php/connexion.php');

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
    echo("test");
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

function ConnectB($DB)
{
    return mysqli_connect("localhost","root","root",$DB);
}

$dbName = ConnectB("reservesandwich");
?>

<html>
    <head>
        <title>Historique de commande</title>
        <meta charset="utf-8"/>
        <link rel="stylesheet" href="../css/historique.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href='https://font.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <script src="https://unpkg.com/bootstrap-table@1.19.1/dist/bootstrap-table.min.js%22%3E"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
        <script src="../JS/script.js"></script>
        <link rel="stylesheet" href='https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css'>
        <script src="http://cdn.datatables.net/plug-ins/1.10.9/i18n/French.json"></script>
        <script src="https://kit.fontawesome.com/4168ad773e.js" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
        <script src="https://cdn.datatables.net/datetime/1.1.2/js/dataTables.dateTime.min.js"></script>
    </head>
    <body>
        <div id="div_historique" class="container">
            <h1><bold>Historique de commande</bold></h1>
            <?php
                    

                // Section suppression //

                if(isset($_POST['idCommandeDelete'])){
                    try
                    {
                        $idCommandeDelete = $_POST['idCommandeDelete'];
                        $delete = $bdd->query("DELETE FROM commande WHERE id_com=$idCommandeDelete");  
                    }catch(PDOExeption $e){
                        die("Erreur lors de l'obtention des données");
                    }
                }

                try
                {
                    $filtres = $bdd->query("SELECT id_hist as id, dateDebut_hist as dateMin, dateFin_hist as dateMax FROM historique WHERE fk_user_id = $idUtilisateur"); // w<here id = l'id du mec (fk_user)
                    $filtres->execute();
                }catch(PDOExeption $e) 
                {
                    die("Erreur lors de l'obtention des données");
                }

                

                try
                {
                    if($filtres->rowCount() != 0)
                    {
                        foreach($filtres as $filtre)
                        {
                            $commandes = $bdd->query("SELECT utilisateur.nom_user as nomUser, utilisateur.prenom_user as prenomUser, sandwich.nom_sandwich as nomSandwich, boisson.nom_boisson as nomBoisson, dessert.nom_dessert as nomDessert, commande.chips_com as chips, date_heure_com as dateHeure, date_heure_livraison_com as dateHeureLivraison, id_com as idCom FROM commande
                                            join utilisateur on fk_user_id=utilisateur.id_user join sandwich on fk_sandwich_id=sandwich.id_sandwich join dessert on fk_dessert_id=dessert.id_dessert join boisson on fk_boisson_id=boisson.id_boisson WHERE fk_user_id = $idUtilisateur AND  date_heure_com BETWEEN '$filtre->dateMin' AND '$filtre->dateMax'"); // w<here id = l'id du mec (fk_user)
                            $commandes->execute();               
                        }

                    }
                    else 
                    {
                        $commandes = $bdd->query("SELECT utilisateur.nom_user as nomUser, utilisateur.prenom_user as prenomUser, sandwich.nom_sandwich as nomSandwich, boisson.nom_boisson as nomBoisson, dessert.nom_dessert as nomDessert, commande.chips_com as chips, date_heure_com as dateHeure, date_heure_livraison_com as dateHeureLivraison, id_com as idCom FROM commande
                                            join utilisateur on fk_user_id=utilisateur.id_user join sandwich on fk_sandwich_id=sandwich.id_sandwich join dessert on fk_dessert_id=dessert.id_dessert join boisson on fk_boisson_id=boisson.id_boisson WHERE fk_user_id = $idUtilisateur order by date_heure_livraison_com desc "); // w<here id = l'id du mec (fk_user)
                        $commandes->execute();
                    }
                    
                }catch(PDOExeption $e) 
                {
                    die("Erreur lors de l'obtention des données");
                }

                
               
                echo 
                "<table border='0' cellspacing='5' cellpadding='5'>
                    <tbody>
                        <tr>";?>
                            
                            <form method="POST" action="">
                        <?php         
                            echo "
                                <td>Date minimum :</td>
                                <td><input type='datetime-local' id='min' name='min' value='2022-04-09T00:00'></td>
                        </tr>
                        <tr>
                                <td>Date maximum :</td>
                                <td><input type='datetime-local' id='max' name='max' value='2022-04-09T01:00'></td>";?>
                                <div id='formulaire_boisson'>
                                    <form method="POST" action="">
                                        <label>Selectionner votre profil</label>
                                        <?php
                                            echo "<select id='utilisateur' name='utilisateur'>";
                                            $idUsers = $bdd->query("SELECT * FROM utilisateur");
                                            foreach($idUsers as $idUser)
                                            {
                                                echo "<option value ='$idUser->id_user'>$idUser->nom_user</option>";
                                            }
                                            echo "</select>";
                                        ?>
                                    </form>
                                </div>
                        <?php
                            
                            echo "
                        </tr>
                        <tr>";
                        if($filtres->rowcount() != 0){
                            $filtresArray = [];
                            foreach($filtres as $filtre)
                            {
                                echo "
                                <td><button id='suppressionFiltre' type='button' data-filtre='$filtre->id' name='suppressionFiltre' class='btn btn-default'>Supprimer les filtres</button></td>";
                                
                            }

                          
                        }
                        else
                        {
                            echo "
                                <td><button type='submit' id='ajoutFiltre' name='ajoutFiltre'>Créer le filtre</button></td>";

                        }
                            echo "
                            </form>
                        </tr>
                    </tbody>
                </table>";
                echo "
                <table id='table1' class='table table-striped'>
                    <thead class='text-center'>
                        <tr>
                            <th>Numéro de commande</th>
                            <th>Nom</th>
                            <th>Prenom</th>
                            <th>Sandwich</th>
                            <th>Boisson</th>
                            <th>Dessert</th>
                            <th>Chips</th>
                            <th>Date de commande</th>
                            <th>Date de livraison</th>
                            <th>Actions</th>
                            </tr>
                    </thead><tbody>";
                    if($commandes->rowCount() != 0)
                    {
                        foreach($commandes as $commande)
                        {
                            $dateLivraisonConverted = date("d/m/Y h:i:s", strtotime($commande->dateHeureLivraison)); // Date convertie en format européen 
                            $dateCommandeConverted = date("d/m/Y h:i:s", strtotime($commande->dateHeure));
                            $chips = "non";
                            if(($commande->chips) == 1)
                            {
                                $chips = "oui";
                            }
                            else{
                                $chips = "non";
                            }
                            echo "<tr>
                            <td>$commande->idCom</td>
                            <td>$commande->nomUser</td>
                            <td>$commande->prenomUser</td>
                            <td>$commande->nomSandwich</td>
                            <td>$commande->nomBoisson</td>
                            <td>$commande->nomDessert</td>
                            <td>$chips</td>
                            <td>$dateCommandeConverted</td>
                            <td>$dateLivraisonConverted</td>
                            <td> <a id='$commande->idCom' class='update' data-toggle='modal' data-target='#myModal'><i class='fa-regular fa-pen-to-square'></i></a> <a id='$commande->idCom' class='delete'><i class='fa-solid fa-trash-can'></i></a></td>                           
                            </tr>";
                        }
                    }            
                    
                echo "</tbody></table>";
                
            ?> 
            <!-- Modal -->
            <div class="modal fade" id="myModal" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button id="bouton_close" type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Modification de la commande</h4>
                        </div>
                        <div class="modal-body">
                            <div class='d-flex justify-content-center'>
                                <div id='formulaire_sandwich'>
                                    <form>
                                        <label>Selectionner votre sandwich</label>
                                        <?php
                                            echo "<select id='sandwich'>";
                                            $sandwichs = $bdd->query("SELECT * FROM sandwich");
                                            foreach($sandwichs as $sandwich)
                                            {
                                                    echo "<option value='$sandwich->id_sandwich'>$sandwich->nom_sandwich</option>";
                                            }
                                            echo "</select>";
                                        ?>
                                    </form>
                                </div>
                                <div id='formulaire_boisson'>
                                    <form>
                                    <label>Selectionner votre boisson</label>
                                        <?php
                                            echo "<select id='boisson'>";
                                            $boissons = $bdd->query("SELECT * FROM boisson");
                                            foreach($boissons as $boisson)
                                            {
                                                echo "<option value='$boisson->id_boisson'>$boisson->nom_boisson</option>";
                                            }
                                            echo "</select>";
                                        ?>
                                    </form>
                                </div>
                                <div id='formulaire_dessert'>
                                    <form>
                                    <label>Selectionner votre dessert</label>
                                        <?php
                                            echo "<select id='dessert'>";
                                            $desserts = $bdd->query("SELECT * FROM dessert");
                                            foreach($desserts as $dessert)
                                            {
                                                echo "<option value='$dessert->id_dessert'>$dessert->nom_dessert</option>";
                                            }
                                            echo "</select>";
                                        ?>
                                    </form>                                   
                                </div>
                                <div id='formulaire_chips'>
                                    <form>
                                    <label>Voulez vous des chips ?</label>
                                        <?php
                                            echo "<select id='chips'>";
                                            $desChips = $bdd->query("SELECT * FROM chips");
                                            foreach($desChips as $chips)
                                            {
                                                echo "<option value='$chips->id'>$chips->valeur</option>";
                                            }
                                            echo "</select>";
                                        ?>
                                    </form>                                   
                                </div>
                                <div id='formulaire_date_livraison'>
                                    <form>
                                        <label for="start">Selectionner une nouvelle date :</label>
                                        <input type="datetime-local" id="dateLivraison" name="trip-start">
                                    </form>
                                </div>
                            </div>    
                        </div>
                        <div id="0" class="modal-footer">
                            <button id="valider" type="button" class="btn btn-default" data-dismiss="modal">Valider</button>
                            <button id="fermer" type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                        </div>
                    </div>
                </div>
            </div>
      </div>
    </body>
</html>
