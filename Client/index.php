<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css" />
    <title>Articles</title>
</head>
<body>
    <div id="header">
        <div>
            <p id="pseudo">Pseudo</p> 
            <button onclick="window.location.href='authentification.php'" id="connexion">Connexion</button>
            <button onclick="<?php
            session_start();
            session_destroy();
            echo 'window.location.href=\'authentification.php\'';
            ?>" id="deconnexion">Déconnexion</button>
        </div>
    </div>

    <div id="body">
        <form action="publication.php" method="get">
            <label for="contenu">Ecrivez votre article : </label>
            <input type="textarea" name="contenu" id="contenu">
            <input type="submit" value="Ajouter" name="ajouter">
        </form>

        <div>
            <?php
                session_start();
                $result = file_get_contents(
                    'http://localhost/API-Gestion-Articles/Serveur/apiApp.php',
                    false,
                    stream_context_create(array('http' => array('method' => 'GET'))) // ou DELETE
                );

                $result = json_decode($result, true, 512, JSON_THROW_ON_ERROR);
                foreach ($result['data'] as $row) {
                    echo "<div class='article'>"
                    . "<div class='contenu'>"
                    . $row['Auteur'] . "<br/>"
                    . $row['Contenu'] . "<br/>"
                    . $row['Like']  . $row['Dislike'] . $row['DatePublication']
                    ."</div>"
                    . "<div class='boutons'>"
                    . "<a href='modification.php?id=". $row['IdArticle']. "'>Modifier</a>"
                    . "<a href='suppression.php?id=". $row['IdArticle']. "'>Supprimer</a> <br/>"
                    ."</div>"
                    ."</div>";
                }
            ?>
        </div>
    </div>
</body>
</html>