<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css" />
    <title>Articles</title>

    <!-- Import du Script -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
</head>
<body>
    <div id="header">
        <div>
            <?php
                session_start();
                if (isset($_SESSION["pseudo"])){
                    echo "<p id='pseudo'>" . $_SESSION["pseudo"] . "</p>
                    <button id='deconnexion' onclick='deconnexion();' name='deconnexion'>Déconnexion</button>";
                } else {
                    echo "<button onclick=\"window.location.href='authentification.php'\" id=\"connexion\">Connexion</button>";
                }
            ?>
        </div>
    </div>

    <div id="body">
        <?php
            if (isset($_SESSION["role"]) && $_SESSION["role"] == "Publisher") {
                echo '<form action="publication.php" method="get">
                        <label for="contenu">Ecrivez votre article : </label>
                        <input type="textarea" name="contenu" id="contenu">
                        <input type="submit" value="Ajouter" name="ajouter">
                    </form>';
            }
        ?>

        <div>
            <?php
                require_once("lienAPI.php");

                if (isset($_SESSION["token"])){
                    $result = file_get_contents(
                        API_APP,
                        false,
                        stream_context_create(array('http' => 
                                            array('method' => 'GET',
                                            'header' => array('Authorization: Bearer ' . $_SESSION["token"] . "\r\n")))) 
                    );
                } else {
                    $result = file_get_contents(
                        API_APP,
                        false,
                        stream_context_create(array('http' =>array('method' => 'GET'))) 
                    );

                }

                $result = json_decode($result, true, 512, JSON_THROW_ON_ERROR);
                foreach ($result['data'] as $row){
                    $ligne = "<div class='article'>"
                                . "<div class='contenu'><b>"
                                . $row['Auteur'] . "</b> ("
                                . $row['DatePublication'] . ") <br/>"
                                . $row['Contenu'] . "<br/>";

                    if (isset($_SESSION["role"])){
                        if ($_SESSION["role"] == "Publisher") {
                            $ligne .= "<div class='like-dislike'>
                                            <div class='btn-like'>
                                                <div class='icon-like-bg'>
                                                    <div class='icon icon-like'></div>
                                                </div>
                                                <div class='nb-like'>" . $row['Like'] . "</div>
                                            </div>
                                            <div class='btn-dislike'>
                                                <div class='icon-dislike-bg'>
                                                    <div class='icon icon-dislike'></div>
                                                </div>
                                                <div class='nb-dislike'>" . $row['Dislike'] . "</div>
                                            </div>
                                        </div>"
                                        . "</div>";
                        } else if ($_SESSION["role"] == "Moderator") {
                            $ligne .= "<div class='like-dislike'>
                                            <div class='btn-like'>
                                                <p>Nombre de likes : </p>"
                                                . $row['Like'] . 
                                            "</div>
                                            <div class='btn-dislike'>
                                                <p>Nombre de dislikes : </p>"
                                                . $row['Dislike'] .
                                            "</div>
                                        </div>
                                        <div class='like-dislike'>
                                            <div class='btn-like like'>"
                                                . implode($row['Utlisateurs ayant like'], "<br/>") .
                                            "</div>
                                            <div class='btn-dislike dislike'>"
                                                . implode($row['Utlisateurs ayant dislike'], "<br/>") .
                                            "</div>
                                        </div>
                                        </div>"
                                        . "<div class='boutons'><a href='suppression.php?id=". $row['IdArticle']. "'>Supprimer</a> <br/></div>";
                        }
                        if (($_SESSION["role"] == "Publisher") && ($_SESSION["pseudo"] == $row['Auteur'])) {
                            $ligne .= "<div class='boutons'><a href='modification.php?id=". $row['IdArticle'] 
                            . "&contenu=" . $row['Contenu'] . "'>Modifier</a><a href='suppression.php?id=". $row['IdArticle']. "'>Supprimer</a> <br/></div>";
                        }
                    } else {
                        $ligne .= "</div>";
                    }
                    echo $ligne . "</div>";
                }
            ?>
        </div>
    </div>

    <script src="script.js"> </script>

</body>
</html>