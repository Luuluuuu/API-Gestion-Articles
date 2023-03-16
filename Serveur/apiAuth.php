<?php
    header("Content-Type:application/json");
    require_once("jwt_utils.php");

    // Tableau d'identifiants (login=>pwd)
    $identifiants = array("test"=>"azerty");

    /// Identification du type de méthode HTTP envoyée par le client
    $http_method = $_SERVER['REQUEST_METHOD'];
    switch ($http_method){
        /// Cas de la méthode POST
        case "POST" :
            // Récupère les données entrées par le Client
            $postedData = file_get_contents('php://input');
            $postedData = json_decode($postedData, true, 512, JSON_THROW_ON_ERROR);

            // Si aucun login ou aucun mdp ne sont renseignés
            if (empty($postedData["login"]) || empty($postedData["mdp"])){
                deliver_response(400, "Veuillez renseigner votre login (login) et votre mot de passe (mdp)", null);

            } else {
                // Récupération des données saisies
                $login = $postedData["login"];
                $pwd = $postedData["mdp"];

                // Si le login n'existe pas
                if (!isset($identifiants[$login])){
                    deliver_response(404, "Votre login n'existe pas.", null);

                // Si le mot de passe ne correspond pas
                } else if ($pwd != $identifiants[$login]){
                    deliver_response(404, "Votre mot de passe est incorrect.", null);

                //Traitement
                } else {
                    $headers = array("alg"=>"HS256", "typ"=>"JWT");
                    $payload = array("username"=>$login, "exp"=>(time() + 360));

                    $jwt = generate_jwt($headers, $payload);
                    deliver_response(200, "OK", $jwt);

                }

            }
        break;
        default:
            // Gestion des erreurs
            deliver_response(405, "Votre méthode n'est pas supportée.", NULL);
        break;
    }

    /// Envoi de la réponse au Client
    function deliver_response($status, $status_message, $data){
        /// Paramétrage de l'entête HTTP, suite
        header("HTTP/1.1 $status $status_message");

        /// Paramétrage de la réponse retournée
        $response['status'] = $status;
        $response['status_message'] = $status_message;
        $response['data'] = $data;

        /// Mapping de la réponse au format JSON
        $json_response = json_encode($response);
        echo $json_response;
    }
?>