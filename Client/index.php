<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css" />
    <title>Articles</title>

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <script>
        function deconnexion() {
            $.ajax({
            url: "deconnexion.php",
            data: {},
            type: "get",
            success: function(msg){
                    window.location.reload();
                }
            })
        }
    </script>
</head>
<body>
    <div id="header">
        <div>
            <?php
                session_start();
                if (isset($_SESSION["pseudo"])){
                    echo "<p id='pseudo'>" . $_SESSION["pseudo"] . "</p>";
                }
            ?>
            <button onclick="window.location.href='authentification.php'" id="connexion">Connexion</button>
            <button id="deconnexion" onclick="deconnexion();" name="deconnexion">Déconnexion</button>
        </div>
    </div>

    <div id="body">
        <?php
            if (isset($_SESSION["pseudo"])){
                echo '<form action="publication.php" method="get">
                    <label for="contenu">Ecrivez votre article : </label>
                    <input type="textarea" name="contenu" id="contenu">
                    <input type="submit" value="Ajouter" name="ajouter">
                </form>';
            }
        ?>
        <div>
            <?php
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