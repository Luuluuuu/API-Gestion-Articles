<?php

    header("Location:index.php");

    if (isset($_GET['ajouter'])) {
        $contenu = $_GET['contenu'];

        $data = array("contenu" => $contenu);
        $data_string = json_encode($data);
        /// Envoi de la requête
        $result = file_get_contents(
            'http://localhost/API-Gestion-Articles/Serveur/apiApp.php', 
            false,
            stream_context_create(array(
                'http' => array(
                    'method' => 'POST',
                    'content' => $data_string,
                    'header' => array(
                        'Content-Type: application/json'."\r\n"
                        .'Content-Length: '.strlen($data_string)."\r\n"
                    )
                )
            ))
        );
        print_r($result);
    }
?>