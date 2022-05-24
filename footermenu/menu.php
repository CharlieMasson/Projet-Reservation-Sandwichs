<section id="Navbar">
  <div class="container-fluid">
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
      <div class="saint_vincent">
        <img class="logo"src="../images/logo_stvincent.png">      
        <a class="navbar-brand" href="#home">Lycée SAINT-VINCENT <br> Enseignement secondaire & supérieur</a>
      </div>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav ml-auto">
          <!--Elements de la navbar-->
          <a class="nav-item nav-link active" href="../index.php"> Accueil <span class="sr-only">(current)</span></a>
          <?php
          if(isset($_SESSION["id_utilisateur"]) && $_SESSION["admin"]== false){
              echo'<a class="nav-item nav-link active" href="../historique/index.php"> Historique <span class="sr-only"></span></a>';
          }

          if(isset($_SESSION["id_utilisateur"]) && $_SESSION["admin"]== false){
              echo'<a class="nav-item nav-link active" href="../reservation/index.php"> Commander <span class="sr-only"></span></a>';
          }

          if(isset($_SESSION["admin"])){
            if($_SESSION["admin"] == true){
              echo'<a class="nav-item nav-link active" href="../cantine_backoffice/index.php"> .Accès au BackOffice. <span class="sr-only"></span></a>';
            }
          }

          if(!est_connecte()){
            echo('<a class="cta" href="../login.php" target="blanc"> <button id="BtnCon">Connexion</button></a>');
          }
          else{
            echo('<a class="cta" href="../deconnexion.php" target="blanc"> <button id="BtnCon">Déconnexion</button></a>');
          }
          ?>

        </div>
      </div>
    </nav> 
  </div>
</section>