<?php 
    require '../php/script_login.php';
    require '../php/auth.php';


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
        header("Location: ../login.php"); //redirige vers la page login; //redirige vers la page login
        exit;
    }


    $role = $nom = $prenom = $email = $mdp = $mdpVerif = $erreur = "";
    date_default_timezone_set('Europe/Paris');

    if(!empty($_POST)){ //si POST n'est pas vide l'utilisateur a envoyé un formulaire
        $role = checkInput($_POST['role']);
        $email = checkInput($_POST['email']);
        $active = 1;
        $nom = checkInput($_POST['nom']);
        $prenom = checkInput($_POST['prenom']);
        $mdp = checkInput($_POST['mdp']);
        $mdpVerif = checkInput($_POST['mdpVerif']);
        $isSuccess = true;

        if ((empty($role)) || (empty($nom)) || (empty($prenom)) || (empty($email)) || (empty($mdp))){ //si un des champs est vide une erreur est retourné
            $erreur = "Auncun champ ne peut être vide";
            $isSuccess = false;
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) { //vérification que l'email est valide
            $erreur = "L'email est invalide";
            $isSuccess = false;
        }

        if ($mdp != $mdpVerif){ //vérification que les mdps correspondent
            $erreur = "Les Mots de Passes ne correspondent pas";
            $isSuccess = false;
        }
        else{
            if(strlen($mdp) >=8){
                if (preg_match("/[\W]/", $mdp)){   
                    if (preg_match("/[0-9]/", $mdp)){
                        $mdp = password_hash($_POST['mdp'], PASSWORD_ARGON2I);
                    }
                    else{
                        $isSuccess = false;
                        $erreur = "Mot de passe non conforme (1 chiffre minimum)";
                    }
                }else {
                    $isSuccess = false;
                    $erreur = "Mot de passe non conforme (1 caractère spécial minimum)";
                }
            }else{
                $isSuccess = false;
                $erreur = "Mot de passe trop court (8 caractères minimum)";
            }
        }

        $pdo = connexion();
        $statement = $pdo->prepare("SELECT * from utilisateur WHERE email_user = ?");
        $statement->execute(array($email));
        $rep = $statement ->fetchColumn(); //rep prend le nombre de ligne ou le mail est égal à la valeur saisie
            if ($rep>0){
                $erreur = "Email déjà utilisé";
                $isSuccess = false;
            }
        $pdo = deconnexion();

        if($isSuccess) { //si tout ce passe bien un utilisateur avec les valeurs donnés est créé
            $pdo = connexion();
            $statement = $pdo->prepare("INSERT INTO utilisateur(role_user, email_user, password_user, nom_user, prenom_user, active_user) values (?, ?, ?, ?, ?, ?)") or die();
            $statement->execute(array($role, $email, $mdp, $nom, $prenom, $active));
            $pdo = deconnexion();
            header("Location: ../cantine_backoffice/index.php");
        }

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
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="../css/backoffice.css">
    </head>

    <body>
        <?php require "menu.php" ?>
        <div class="container-fluid">
            <section id="creationProjet">
                <h2>Formulaire de Création de Compte</h2>
                    <form class="form" action="ajout.php" method="post" enctype="multipart/form-data">
                        <div class="formulaire">
                            <ul>
                                <li class="erreur"><?php echo $erreur?></li>
                                <li>
                                    <label for="prenom"> Prénom : </label>
                                    <input type="text" name="prenom" maxlength="50" placeholder="Prénom" value="<?php echo $prenom?>"/>
                                </li>
                                <li>
                                    <label for="nom"> Nom : </label>
                                    <input type="text" name="nom" maxlength="50" placeholder="Nom" value="<?php echo $nom?>"/>
                                </li>
                                <li>
                                    <label for="email"> Email : </label>
                                    <input type="text" name="email" maxlength="50" placeholder="Email" value="<?php echo $email?>"/>
                                </li>
                                <li>
                                    <label for="mdp"> Mot de Passe : </label>
                                    <input type="password" name="mdp" maxlength="50"/>
                                </li>
                                <li>
                                    <label for="mdpVerif"> Vérification Mot de Passe : </label>
                                    <input type="password" name="mdpVerif" maxlength="50"/>
                                </li>
                                <li>
                                    <label for="role"> Role : </label>
                                    <select name="role">
                                        <option value="u"> Utilisateur </option>
                                        <option value="a"> Administrateur </option>
                                    </select>
                                </li>
                            </ul>
                        
                            <div class="buttons">
                                <button type="submit" class="btn btn-success ajout"name="submit"> <span>Valider <i class="fas fa-check"></i></span></button>
                                <a class="btn btn-secondary" href="../cantine_backoffice/index.php">Annuler <i class="fas fa-times"></i></a>
                            </div>
                        </div>    
                </form>
            </section>
        </div>
    </body>
</html>