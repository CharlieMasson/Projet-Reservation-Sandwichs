<?php 
    require 'php/script_login.php';
    $co = connexion();
    $statement = $co->prepare("SELECT * FROM accueil WHERE id_accueil = 1");
    $statement->execute(array());
    $item = $statement->fetch();
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>LyceeStVincent / Cantine</title>
    <link rel="stylesheet" href="style.css" type="text/css">
    <!--MetaName-->
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
    integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
    crossorigin="anonymous"></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
    integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
    crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" 
    integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

    <link rel="stylesheet" href="style.css">
</head>
    <body>
    <embed class="img_menu" src="images/menu.pdf" alt="..." height="700px" width="1000px" type="application/pdf"><br>
    <h3 style="margin-top: -20px"><a class="btn btn-outline-secondary btn-sm" href="index.php"><span class="bi-arrow-left"></span> Retour</a></h3>
    <?php
    require 'footer.php'
    ?>
    </body>
</html>