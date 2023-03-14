<?php

    header("Location:client.php");

    if (isset($_GET['ajouter'])) {
    
        $entree = $_GET['entree'];

        $data = array("phrase" => $entree);
        $data_string = json_encode($data);
        /// Envoi de la requête
        $result = file_get_contents(
            'http://www.kilya.biz/api/chuckn_facts.php', // remplacer lien par le serveur
            null,
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
    
    }
?>