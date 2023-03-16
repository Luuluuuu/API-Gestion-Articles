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
            <button onclick="window.location.href='index.php'">Déconnexion</button>
        </div>
    </div>

    <div id="body">
        <form action="publication.php" method="get">
            <label for="entree">Texte à ajouter : </label>
            <input type="text" name="entree" id="entree">
            <input type="submit" value="Ajouter" name="ajouter">
        </form>

        <div>
            <?php
                $result = file_get_contents(
                    'http://localhost/API-Gestion-Articles/Serveur/apiApp.php', // remplacer lien par le serveur
                    false,
                    stream_context_create(array('http' => array('method' => 'GET'))) // ou DELETE
                );

                $result = json_decode($result, true, 512, JSON_THROW_ON_ERROR);
                foreach ($result['data'] as $row) {
                    echo "<div class='article'>" . $row['Auteur']
                    . "<a href='modification.php?id=". $row['IdArticle']. "'>Modifier</a>"
                    . "<a href='suppression.php?id=". $row['IdArticle']. "'>Supprimer</a> <br/>"
                    . $row['Contenu'] . "<br/>"
                    . $row['Like']  . $row['Dislike'] . $row['DatePublication'] .
                    "</div>";
                }
            ?>
        </div>
    </div>
</body>
</html>