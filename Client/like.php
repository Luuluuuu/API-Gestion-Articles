<?php
    session_start();
    require_once("lienAPI.php");
    $data = array("ALike" => $_GET["like"]);
    $data_string = json_encode($data);
    /// Envoi de la requête
    $result = file_get_contents(
        API_APP, 
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