<?php 
    require '../php/script_login.php';
    require '../php/auth.php';

    if(!empty($_GET['id'])){
        $id = checkInput ($_GET['id']);
    }


    if(!est_connecte()){ 
        header('Location: ../login.php'); //si l'utilisateur n'est pas connecté il est redirigé vers login.php
        exit();
    }

    if(!$_SESSION['admin'] == True){
      header('Location: ../cantine_backoffice/error.php'); //si l'utilisateur n'est pas un administrateur il est redirigé vers error.php
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

    if(!empty($_POST)){
        $pdo = connexion();
        $id= checkInput ($_POST['id']);
        $statement = $pdo->prepare("SELECT COUNT(*) FROM commande WHERE fk_user_id = ?"); //selectionne le nombre de lignes dans la table commande ou l'id de l'utilisateur apparait
        $statement -> execute(array($id));
        $rep = $statement ->fetchColumn();
        if ($rep == 0){ //si l'utilisateur apparait dans 0 ligne alors il est directement supprimer car il n'a pas de commandes
            $statement = $pdo->prepare("DELETE FROM utilisateur WHERE id_user = ?");
            $statement->execute(array($id));
            header("Location: ../cantine_backoffice/index.php");
        }
        else{
            $aCommande = True; //sinon aCommande prend vrai
        }
        $pdo = deconnexion();
    }

    if((ISSET($aCommande))&&(ISSET($_POST['validDesact']))){ //si aCommande est vrai et que POST n'est pas vide, cela veut dire que l'utilisateur qu'on veut supprimer a des commandes
        $pdo = connexion();
        $statement = $pdo->prepare("UPDATE utilisateur SET active_user=0 WHERE id_user = ? "); //au lieu de le supprimer il devient inactif
        $statement->execute(array($id));
        header("Location: ../cantine_backoffice/index.php");
    }

    function checkInput($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

?>
<!DOCTYPE html>
<html lang="fr">
<meta charset="utf-8"/>
    <head>
        <!-- Bootstrap, fontawesome, Jquerry, css -->
        <meta charset="utf-8"/>
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="../css/backoffice.css">
    </head>

    <body>
        <?php require "menu.php" ?>
        <form class="form" role="form" action="supprimer.php?id=<?php echo $id?>" method="post"> 
            <div class="supprimer">
                <input type="hidden" name="id" value="<?php echo $id ?>">
                <?php 
                    if (!ISSET($aCommande)){
                        echo '<p> Souhaitez-vous vraiment supprimer cet utilisateur? <button type="submit" style="margin-right: 5px;" class="btn btn-danger">Supprimer <i class="fas fa-trash-alt"></i></button> <a class="btn btn-secondary" href="index.php">Annuler <i class="fas fa-times"></i></a> </p>';
                    }
                    else{
                        echo'<p> Une commande a déjà été passé sur ce compte, celui-ci ne peut donc pas être supprimé pour l&#8217;instant. Voulez-vous le désactiver à la place? <button type="submit" style="margin-right: 5px;" class="btn btn-danger">Désactiver <i class="fas fa-trash-alt"></i></button> <a class="btn btn-secondary" href="index.php">Annuler <i class="fas fa-times"></i></a></p>';
                        echo'<input type="hidden" name="validDesact" value="True">';
                    }
                    
                ?>
            </div>  
        </form>
    </body>
</html>