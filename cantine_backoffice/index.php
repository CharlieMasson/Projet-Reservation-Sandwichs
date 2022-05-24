<?php 
    require '../php/script_login.php';
    require '../php/auth.php';


    if(!est_connecte()){ 
        header('Location: ../login.php'); //si l'utilisateur n'est pas connecté il est redirigé vers login.php
        exit();
    }

    if(!$_SESSION['admin'] == true){
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

    <div class="container-fluid">
      <section id="tableau">
        <h3> Liste Des Comptes </h3>
        <table class="table table-striped table-bordered">
          <thead>
            <tr>
              <th>ID</th>
              <th>Role</th>
              <th>Email</th>
              <th>Nom</th>
              <th>Prénom</th>
              <th>Actif?</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $pdo = connexion(); 
            $statement = $pdo->query('SELECT * from utilisateur');
            while($utilisateur = $statement->fetch())
           {
              echo '<tr>';
              echo '<td>' . $utilisateur['id_user'] . '</td>';
              echo '<td>' . $utilisateur['role_user'] . '</td>';
              echo '<td>' . $utilisateur['email_user'] . '</td>';
              echo '<td>' . $utilisateur['nom_user'] . '</td>';
              echo '<td>' . $utilisateur['prenom_user'] . '</td>';
              if ($utilisateur['active_user'] == 1){
                echo '<td> Oui </td>';
              }
              else {
                echo '<td> Non </td>';
              }
              echo '<td width=250>';
              echo '
                <a class="btn btn-primary" href="modifier.php?id=' . $utilisateur['id_user'] . '">Modifier <i class="fas fa-pen"></i></a>
                <a class="btn btn-danger" href="supprimer.php?id=' . $utilisateur['id_user'] . '">Supprimer <i class="fas fa-times"></i></a>';
              echo '</td>';
              echo '</tr>';
            }

            $pdo = deconnexion();
            ?>
          </tbody>
        </table>
      </section>
    </div> 
  </body>
</html>