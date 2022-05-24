<?php 
    require '../php/script_login.php';
    require '../php/auth.php';
    
    if(!empty($_GET['id']))
    {
        $id = checkInput($_GET['id']);
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


    $role = $nom = $prenom = $email = $mdp = $mdpVerif = $erreur = $actif = ""; 

    if(!empty($_POST)){ //si POST n'est pas vide l'utilisateur a envoyé un formulaire
        $role = checkInput($_POST['role']);
        $email = checkInput($_POST['email']);
        $actif = checkInput($_POST['actif']);
        $nom = checkInput($_POST['nom']);
        $prenom = checkInput($_POST['prenom']);
        if(!empty($_POST['mdp']) || !empty($_POST['mdpVerif'])){ //if pour savoir si le mot de passe a été changé ou non pour savoir sil il doit être mis à jour
            $mdp = checkInput($_POST['mdp']);
            $mdpVerif = checkInput($_POST['mdpVerif']);
            $mdpChangé = true;
        }
        else{
            $mdpChangé = false;
        }
        $isSuccess = true;

        if ((empty($role)) || (empty($nom)) || (empty($prenom)) || (empty($email))){ //si un des champs sauf mdp est vide une erreur est retourné
            $erreur = "Seul le champ Mot de Passe peut être laissé vide";
            $isSuccess = false;
        }

        $pdo = connexion();
        $statement = $pdo->prepare("SELECT email_user from utilisateur WHERE id_user = ?"); //if pour vérifier si email a été changé et si il n'existe pas déjà dans la bdd
        $statement->execute(array($id));
        $utilisateur = $statement->fetch();
        $oldEmail = $utilisateur['email_user'];
        if ($email != $oldEmail){
            $statement = $pdo->prepare("SELECT * from utilisateur WHERE email_user = ?");
            $statement->execute(array($email));
            $rep = $statement ->fetchColumn(); //rep prend le nombre de ligne ou le mail est égal à la valeur saisie
            if ($rep>0){
                $erreur = "Email déjà utilisé";
                $isSuccess = false;
            }
        }
        $pdo = deconnexion();

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) { //vérification que l'email est valide
            $erreur = "L'email est invalide";
            $isSuccess = false;
        }

        if ($mdpChangé){
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
        }


        if($isSuccess&&$mdpChangé) {
            $pdo = connexion();
            $statement = $pdo->prepare("UPDATE utilisateur set role_user = ?, email_user = ?, password_user = ?, nom_user = ?, prenom_user = ?, active_user = ? WHERE id_user = ?") or die();
            $statement->execute(array($role, $email, $mdp, $nom, $prenom, $actif, $id));
            $pdo = deconnexion();
            header("Location: ../cantine_backoffice/index.php"); //le compte visé est mis à jour est l'utilisateur redirigé vers le backoffice
        }
        elseif($isSuccess) {
            $pdo = connexion();
            $statement = $pdo->prepare("UPDATE utilisateur set role_user = ?, email_user = ?, nom_user = ?, prenom_user = ?, active_user = ? WHERE id_user = ?") or die();
            $statement->execute(array($role, $email, $nom, $prenom, $actif, $id));
            $pdo = deconnexion();
            header("Location: ../cantine_backoffice/index.php");
        }

    }
    else { //si POST est vide: connexion à la bdd afin de récupérer les valeurs de l'utilisateur ciblé et de les mettres dans le formulaire en value
        $pdo = connexion(); 
        $statement = $pdo->prepare("SELECT * from utilisateur WHERE id_user = ?");
        $statement->execute(array($id));
        $utilisateur = $statement->fetch();
        $pdo = deconnexion();
        $role = $utilisateur['role_user'];
        $email = $utilisateur['email_user'];
        $nom = $utilisateur['nom_user'];
        $prenom = $utilisateur['prenom_user'];
        $actif = $utilisateur['active_user'];
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
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="../css/backoffice.css">
    </head>

    <body>
        <?php require "menu.php" ?>
        <div class="container-fluid">
            <section id="creationProjet">
                <h2>Formulaire de Modification de Compte</h2>
                    <form class="form" action="modifier.php?id=<?php echo $id ?>" method="post" enctype="multipart/form-data">
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
                                    <span>(laisser vide pour ne pas modifier)</span>
                                </li>
                                <li>
                                    <label for="role"> Role : </label>
                                    <select name="role">
                                        <?php if ($role == "A"){
                                            echo '<option value="a"> Administrateur </option>';
                                            echo '<option value="u"> Utilisateur </option>';
                                        }
                                        else{
                                            echo '<option value="u"> Utilisateur </option>';
                                            echo '<option value="a"> Administrateur </option>';
                                        }
                                        ?>
                                    </select>
                                </li>
                                <li>
                                    <label for="actif"> Actif : </label>
                                    <select name="actif">
                                        <?php if ($actif == "0"){
                                            echo '<option value="0"> Non </option>';
                                            echo '<option value="1"> Oui </option>';
                                        }
                                        else{
                                            echo '<option value="1"> Oui </option>';
                                            echo '<option value="0"> Non </option>';
                                        }
                                        ?>
                                    </select>
                                </li>
                            </ul>
                        </div>
                    <div class="buttons">
                        <button type="submit" class="btn btn-success ajout"name="submit"> <span>Valider <i class="fas fa-check"></i></span></button>
                        <a class="btn btn-secondary" href="../cantine_backoffice/index.php">Annuler <i class="fas fa-times"></i></a>
                    </div>
                </form>
            </section>
        </div>
    </body>
</html>