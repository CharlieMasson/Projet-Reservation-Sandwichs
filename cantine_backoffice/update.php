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

    if (!empty($_GET['id'])) {
        $id = checkInput($_GET['id']);
    }

    $idError = $texteError = $lienError = $texte = $lien = $pdfError = "";

    if (!empty($_POST)) {
        $texte = checkInput($_POST['texte_accueil']);
        $pdf = checkInput($_FILES['pdf']['name']);
        $pdfPath = "../images/menu.pdf";
        $pdfExtension = pathinfo($pdf, PATHINFO_EXTENSION);
        $isSuccess = true;
        $isUploadSuccess= false;

        $isImageUpdated = true;
        if(empty($pdf)){
            $isImageUpdated = false;
        }
        else{
            $isUploadSuccess = true;
            if (!empty(checkInput($_FILES['pdf']['name']))){
                if ($pdfExtension != "pdf") {
                $pdfError = "Le fichier n'est pas un pdf";
                $isUploadSuccess = false;
                }
                if($_FILES["pdf"]["size"] > 700000) {
                $pdfError = "Le fichier est trop lourd";
                $isUploadSuccess = false;
                }
            }
        }

        if($isUploadSuccess) {
            if (!empty(checkInput($_FILES['pdf']['name'])) && $isUploadSuccess){
                if(!move_uploaded_file($_FILES["pdf"]["tmp_name"], $pdfPath)) {
                    $pdfError = "Il y a eu une erreur lors de l'upload";
                    $isUploadSuccess = false;
                } 
            }
        }

        if (empty($texte)) {
            $texteError = 'Ce champ ne peut pas être vide';
            $isSuccess = false;
        }  

        if (($isSuccess && $isUploadSuccess && $isImageUpdated) || ($isSuccess && !$isImageUpdated)) { 
            $db = connexion();
            {   
                $statement = $db->prepare("SELECT lien_pdf FROM accueil WHERE id_accueil= ?");
                $statement->execute(array(1));
                $accueil = $statement->fetch();
                if (empty($pdf)){
                    $pdf = $accueil['lien_pdf'];
                }
                $statement = $db->prepare("UPDATE accueil  set texte_accueil = ?, lien_pdf = ? WHERE id_accueil = ?");
                $statement->execute(array($texte,$pdf,$id));
            }
            $db = deconnexion();
            header("Location: index.php");
              
        }
    }else {
        $db = connexion();
        $statement = $db->prepare("SELECT * FROM accueil where id_accueil = ?");
        $statement->execute(array($id));
        $item = $statement->fetch();
        $texte    = $item['texte_accueil'];
        $lien          = $item['lien_pdf'];
        $db = deconnexion();
    }

    function checkInput($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }

?>



<!DOCTYPE html>
<html>
    <head>
    <title>Cantine</title>
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
        <div class="container-fluid">
            <h2>Modifier la page d'accueil</h2>
            <br>
            <form class="form" action="<?php echo 'update.php?id='.$id;?>" role="form" method="post" enctype="multipart/form-data">
                <br>
                <ul>
                    <br>
                    <li>
                        <label class="form-label" for="texte_accueil">Texte :</label>
                        <textarea class="form-control" id="texte_accueil" name="texte_accueil" placeholder="Texte"><?php echo $texte;?></textarea>
                        <span class="help-inline"><?php echo $texteError;?></span>
                    </li>
                    <br>
                    <li>
                        <label class="form-label" for="lien_pdf">PDF :</label>
                        <input type="file" class="form-control" id="pdf" name="pdf" placeholder="Lien">
                        <span class="help-inline"><?php echo $pdfError;?></span>
                    </li>
                    <br>
                </ul>
                <div class="buttons">
                    <button type="submit" class="btn btn-success ajout"name="submit"> <span>Valider <i class="fas fa-check"></i></span></button>
                    <a class="btn btn-secondary" href="../cantine_backoffice/index.php">Annuler <i class="fas fa-times"></i></a>
                </div>
            </form>
        </div>   
    </body>
</html>
