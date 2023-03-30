<?php
    session_start();
    $data = array("ADislike" => $_GET["dislike"]);
    $data_string = json_encode($data);
    /// Envoi de la requête
    $result = file_get_contents(
        'http://localhost/API-Gestion-Articles/Serveur/apiApp.php?id=23', 
        false,
        stream_context_create(array(
            'http' => array(
                'method' => 'PATCH',
                'content' => $data_string,
                'header' => array(
                    'Content-Type: application/json'."\r\n"
                    .'Content-Length: '.strlen($data_string)."\r\n"
                    .'Authorization: Bearer ' . $_SESSION["token"] . "\r\n"
                )
            )
        ))
    );
?>