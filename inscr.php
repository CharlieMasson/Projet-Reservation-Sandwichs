<!DOCTYPE html>
<html>
<head>
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
    <link rel="stylesheet" href="css/main.css">

    <title>INSCRIPTION</title>
    <meta charset="utf-8">

</head>
<body>
    
<?php 
    require 'php/script_login.php';
    require 'php/auth.php';

    $nom = $prenom = $email = $password = $repeatpassword = $erreur = $sendForm = $error = $errorEmail = "";
    date_default_timezone_set('Europe/Paris');

    if(!empty($_POST)){ //si POST n'est pas vide l'utilisateur a envoyé un formulaire
        $nom = checkInput($_POST['nom_user']);
        $prenom = checkInput($_POST['prenom_user']);
        $email = checkInput($_POST['email_user']);
        $repeatpassword = checkInput($_POST['repeatpassword']);
        $password = checkInput($_POST['password_user']);
        $role = "u";
        $active = 1;
        $isSuccess = true;

        if ((empty($nom)) || (empty($prenom)) || (empty($email)) || (empty($password)) || (empty($repeatpassword))){ //si un des champs est vide une erreur est retourné
            $erreur = "Auncun champ ne peut être vide";
            $isSuccess = false;
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) { //vérification que l'email est valide
            $erreur = "L'email est invalide";
            $isSuccess = false;
        }
        
        // Selection des données dans les tables 'utilisateur' à l'aide d'une requête préparée
            $pdo =  connexion();
            $check = $pdo->prepare('SELECT email_user FROM utilisateur WHERE email_user=:email_user');
            // On execute la requête
            $check->execute(array(
                'email_user' => $_POST['email_user']
                ))or die(print_r($check->errorInfo())); // On traque l'erreur s'il y en a une
            $num_rows = $check->fetchColumn(); 

            if($num_rows!=0){
                    $errorEmail = "Cet email est deja inscrit !";
                    $isSuccess = false;
                }

            if ($password != $repeatpassword){ //vérification que les mdps correspondent
                $error = "Les Mots de Passes ne correspondent pas";
                $isSuccess = false;
            }
            else{
                if(strlen($password) >=8){
                    if (preg_match("/[\W]/", $password)){   
                        if (preg_match("/[0-9]/", $password)){
                            $password = password_hash(checkInput($_POST['password_user']), PASSWORD_ARGON2I);
                        }
                        else{
                            $isSuccess = false;
                            $error = "Mot de passe non conforme (1 chiffre minimum)";
                        }
                    }else {
                        $isSuccess = false;
                        $error = "Mot de passe non conforme (1 caractère spécial minimum)";
                    }
                }else{
                    $isSuccess = false;
                    $error = "Mot de passe trop court (8 caractères minimum)";
                }
            }
            

            if($isSuccess) { //si tout ce passe bien un utilisateur avec les valeurs donnés est créé
                $pdo = connexion();
                $statement = $pdo->prepare("INSERT INTO utilisateur (role_user, email_user, password_user, nom_user, prenom_user, active_user) values (?, ?, ?, ?, ?, ?)") or die();
                $statement->execute(array($role, $email,$password, $nom, $prenom, $active));
                $sendForm = "Inscription réussie !";
                $pdo = deconnexion();
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

<h1>Inscription<h1>
        <form action="" method="post">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="gauche">
                        <div class="form-Inscr">
                            <h3>Nom :</h3>
                            <input class="form-control" type="text" name="nom_user" placeholder="Nom" value="<?php echo $nom?>">
                        </div>
                            <h3 style="margin-top: 17px">Prenom :</h3>
                        <div class="form-Inscr">
                            <input class="form-control" name="prenom_user" placeholder="Prenom" value="<?php echo $prenom?>">
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="Droite">
                        <div class="form-Inscr">
                            <h3>Email :</h3>
                            <input class="form-control" type="text" name="email_user" placeholder="Email" value="<?php echo $email?>">
                            <p class="error"><?php echo $errorEmail ;?></p><p class="error"><?php echo $erreur ;?></p>
                        </div>
                        <div class="form-Inscr">
                            <h3><span style="color: brown"> * </span>Mot de passe :</h3>
                            <input class="form-control" type="password" name="password_user" placeholder="Mot de passe">
                        </div>
                        <div class="form-Inscr">
                            <input class="form-control" type="password" name="repeatpassword" placeholder="Répéter le mot de passe">
                            <p class="error"><?php echo $error;?></p>
                        </div>
                    </div>
                </div>
            </div>
            <a class="info">* Le mot de passe doit contenir 8 caractères minimum + 1 chiffre minimum + 1 caractère spécial minimum.</a><br>
            <button type="submit" class="btnValider">S'inscrire</button>
            
            <p class="validate"><?php echo $sendForm ?></p>
        </form>
        <h3><a class="btn btn-outline-secondary btn-sm" href="index.php"><span class="bi-arrow-left"></span> Retour</a></h3>
        <h3>Vous venez de vous inscrire ?</h3>
        <h3><a href="login.php">Connectez-vous !</a></h3>
        
        <?php
        require 'footermenu/footer.php';
        ?>
</body>
</html>
