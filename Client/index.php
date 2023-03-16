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
    <div id="auth">
        <form class="article" action="apiAuth.php" method="post">
            <label for="login">Login : </label>
            <input type="text" name="login" id="login"><br/>
            <label for="mdp">Mot de passe : </label>
            <input type="password" name="mdp" id="mdp"><br/>
            <input type="submit" value="Connexion" name="connexion">
        </form>
    </div>
</body>
</html>