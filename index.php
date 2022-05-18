<?php
  require 'php/script_login.php';
  require 'php/auth.php';
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
  <!--Navbar-->
  <section id="Navbar">
    <div class="container-fluid">
      <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="saint_vincent">
          <img class="logo"src="images/logo_stvincent.png">      
          <a class="navbar-brand" href="#home">Lycée SAINT-VINCENT <br> Enseignement secondaire & supérieur</a>
        </div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">

          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
          <div class="navbar-nav ml-auto">
            <!--Elements de la navbar-->
            <a class="nav-item nav-link active" href="#accueil"> Accueil <span class="sr-only">(current)</span></a>
            <?php
            session_start();
            if(isset($_SESSION["admin"])){
              if($_SESSION["admin"] == true){
                echo'<a class="nav-item nav-link active" href="cantine_backoffice/index.php"> .Accès au BackOffice. <span class="sr-only"></span></a>';
              }
            }

            if(!est_connecte()){
              echo('<a class="cta" href="login.php" target="blanc"> <button id="BtnCon">Connexion</button></a>');
            }
            else{
              echo('<a class="cta" href="deconnexion.php" target="blanc"> <button id="BtnCon">Déconnexion</button></a>');
            }
            ?>

          </div>
        </div>
      </nav> 
    </div>
  </section>

  <!--Accueil-->
  <section>
    <div class="accueil">
      <h1> Bienvenue </h1>
      <h2 class="text-muted"> Présentation du site </h2>
    </div>
    <?php
      $co = connexion();
      $statement = $co->query('SELECT texte_accueil
      from accueil');
      while($accueil = $statement->fetch())
      {
        echo '<div class="container paragraphe">';
        echo '<p><i>' . $accueil['texte_accueil']. '</i></p>';
        echo '</div>';
      }
      $co = deconnexion();
    ?>


  </section>

  <!--Menu-->
  <section id="menu">
    <div>
      <h2 class="text-muted"> Voici le menu de la semaine </h2>
      <embed class="img_menu" src="images/menu.pdf" alt="..." height="500px" width="700px" type="application/pdf"><br><br>
      <a class="cta" target="blank" href="menuzoom.php" alt="..."> <button id="BtnZoom"> Zoomer sur le menu </button></a>
    </div>
  </section>
  <section id="footer">
    <?php
      require ('footer.php')
    ?>
  </section>

</body>
</html>