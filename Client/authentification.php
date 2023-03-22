<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css" />
    <title>Accueil</title>
</head>
<body>
<form action="clientAuth.php" method="post">
        <div id="auth">
            <h1>Connexion</h1>
            <input type="text" name="login" id="login" placeholder="Entrez votre identifiant"><br/>
            <input type="password" name="mdp" id="mdp" placeholder="Entrez votre mot de passe"><br/>
            <input type="submit" id="submit" value="Connexion" name="connexion"><br/>
            <?php
                if (isset($_GET['erreur'])) {
                    echo $_GET['erreur'];
                }
            ?>
        </div>
    </form>
</body>
</html>