<?php

    session_start();
    if (isset($_POST['connexion'])) {

        // header("Location:index.php");
    
        $login = $_POST['login'];
        $mdp = $_POST['mdp'];

        ////////////////// Cas des méthodes POST et PUT //////////////////
        /// Déclaration des données à envoyer au Serveur
        $data = array("login" => $login,"mdp" => $mdp);
        $data_string = json_encode($data);
        /// Envoi de la requête
        
        try {
            $result = @file_get_contents(
                'http://localhost/API-Gestion-Articles/Serveur/apiAuth.php', // remplacer lien par le serveur
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
            $result = json_decode($result, true, 512, JSON_THROW_ON_ERROR);
            $_SESSION["token"] = $result["data"];
            header("Location:index.php");
        } catch (\JsonException $exception) {
            if (strpos($http_response_header[0], "404")){
                $erreur = explode("404 ", $http_response_header[0])[1];
                header("Location:authentification.php?erreur=$erreur");
             } else if (strpos($http_response_header[0], "400")) {
                $erreur = explode("400 ", $http_response_header[0])[1];
                header("Location:authentification.php?erreur=$erreur");
             }
        }      
    }
        
?>