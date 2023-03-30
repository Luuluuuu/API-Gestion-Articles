<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
    <body>
        <form action="modification.php" method="get">
            <input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>">
            <label for="contenu">Texte à modifier : </label>
            <input type="text" name="contenu" value="<?php echo $_GET["contenu"]; ?>" id="contenu">
            <input type="submit" value="Modifier" name="modifier">
        </form>

        <?php

            if (isset($_GET['modifier'])) {
                session_start();
                header("Location:index.php");
                
                $contenu = $_GET['contenu'];
                ////////////////// Cas des méthodes POST et PUT //////////////////
                /// Déclaration des données à envoyer au Serveur
                $data = array("id" => $_GET["id"],"contenu" => $contenu, "pseudo" => $_SESSION["pseudo"]);
                $data_string = json_encode($data);
                /// Envoi de la requête
                $result = file_get_contents(
                    'http://localhost/API-Gestion-Articles/Serveur/apiApp.php?id=' . $_GET["id"], 
                    false,
                    stream_context_create(array(
                        'http' => array(
                            'method' => 'PUT',
                            'content' => $data_string,
                            'header' => array(
                                'Content-Type: application/json'."\r\n"
                                .'Content-Length: '.strlen($data_string)."\r\n"
                                .'Authorization: Bearer ' . $_SESSION["token"] . "\r\n"
                            )
                        )
                    ))
                );
            
            }
                
        ?>

        
    </body>
</html>