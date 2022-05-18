<?php

require 'php/auth.php';
require 'php/script_login.php';
ini_set("max_execution_time", "36");

$pdo = connexion();


if (!empty($_POST['login']) && !empty($_POST['mdp'])){ //test pour voir si login et mdp ne sont pas vide
    $mdp = checkInput($_POST['mdp']);
    $login = stripslashes(str_replace(array('/', '(', ')', '*', '#', '<', '>', '$'), '',$_POST['login'])); //enlève tout les symboles dangeureux de login pour empecher une injection
    $statement = $pdo->prepare("SELECT COUNT(*) FROM utilisateur WHERE utilisateur.email_user = ?"); //test pour voir si login est dans la bdd
    $statement->execute(array($login)); 
    $rep = $statement ->fetchColumn(); //rep prend le nombre de ligne ou login est égal à la valeur saisie
        if ($rep == 1){ //si rep est égale à 1 (qu'il y'a une ligne dans la table qui correspond au login)
            $statement = $pdo->prepare("SELECT utilisateur.password_user FROM utilisateur WHERE utilisateur.email_user = ?"); //selectionne le mdp hashé du login
            $statement->execute(array($login));
            $utilisateur = $statement->fetch();
            echo $utilisateur['password_user'];
            if (password_verify($mdp, $utilisateur['password_user'])){ //verifie que le mot de passe correspond au hash
                session_start(); //démarre la session
                $_SESSION['connecte'] = 1;
                $_SESSION['timestamp'] = time();
                $statement = $pdo->prepare("SELECT COUNT(*) FROM utilisateur WHERE utilisateur.role_user = 'A' AND utilisateur.email_user = ?");
                $statement->execute(array($login));
                $rep = $statement ->fetchColumn();
                if ($rep == 1){ //if pour voir si l'utilisateur est un administrateur ou non
                    $_SESSION['admin'] = true;
                }
                header('Location: index.php');
                exit();
            }
            else {
                $erreur = "Identifiants incorrects";
                echo "test1";
            }
        } else {
            $erreur = "Identifiants incorrects";
        }
} else {
    $erreur = "Tous les champs doivent être remplis";
}
$pdo = deconnexion();

function checkInput($data){
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
    
        
        <!-- Bootstrap, fontawesome, Jquerry -->
        <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3"
        crossorigin="anonymous"
        />

        <link
        rel="stylesheet"
        href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
        />
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="style.css">
        <script type="text/javascript">
    // Mettez le code javascript ici.
    </script>
    </head>

    <body>

        <?php if (ISSET($erreur)):?>
        <div class="alert alert-danger">
            <?= $erreur; ?>
        </div>
        <?php endif; ?>
        <h1>Connexion</h1>
        <form action="" method="post">
            <div class="form-group">
                <input class="form-control" type="text" name="login" placeholder="Identifiant">
            </div>
            <div class="form-group">
                <input class="form-control" type="password" name="mdp" placeholder="Mot de Passe">
            </div>
            <button type="submit" class="btnConn">Se Connecter</button>
        </form>
            <a href="inscr.php"><button type="submit" class="btnInscr">S'inscrire</button></a>
            <h3><a class="btn btn-outline-secondary btn-sm" href="index.php"><span class="bi-arrow-left"></span> Retour</a></h3>
            <?php
            require 'footer.php';
            ?>
    </body>
</html>