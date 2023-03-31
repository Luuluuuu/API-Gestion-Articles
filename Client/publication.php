<?php
    session_start();
    header("Location:index.php");
    require_once("lienAPI.php");

    if (isset($_GET['ajouter'])) {
        $contenu = $_GET['contenu'];

        $data = array("contenu" => $contenu);
        $data_string = json_encode($data);
        /// Envoi de la requête
        $result = file_get_contents(
            API_APP, 
            false,
            stream_context_create(array(
                'http' => array(
                    'method' => 'POST',
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