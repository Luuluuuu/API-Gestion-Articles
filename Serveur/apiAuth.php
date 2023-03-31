<?php

    header("Content-Type:application/json");
    require_once("jwt_utils.php");
    require_once("connexionBDD.php");

    $linkpdo = connexion();

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
                deliver_response(404, "Veuillez renseigner votre login et votre mot de passe.", null);

            } else {
                // Récupération des données saisies
                $login = $postedData["login"];
                $pwd = $postedData["mdp"];

                $res = $linkpdo->prepare("SELECT NomUtilisateur FROM utilisateur where NomUtilisateur = ?");
                $res->execute(array($login));

                $res2 = $linkpdo->prepare("SELECT MotDePasse FROM utilisateur where NomUtilisateur = ? AND MotDePasse = ?");
                $res2->execute(array($login, $pwd));

                // Si le login n'existe pas
                if ($res->rowCount() == 0){
                    deliver_response(404, "Votre login n'existe pas.", null);

                // Si le mot de passe ne correspond pas
                } else if ($res2->rowCount() == 0){
                    deliver_response(404, "Votre mot de passe est incorrect.", null);

                //Traitement
                } else {

                    //Récupération du rôle
                    $res3 = $linkpdo->prepare("SELECT RoleU, IdUtilisateur
                                                FROM utilisateur 
                                                WHERE NomUtilisateur = ? 
                                                AND MotDePasse = ?");
                    $res3->execute(array($login, $pwd));
                    $row = $res3->fetch(PDO::FETCH_ASSOC);

                    $headers = array("alg"=>"HS256", "typ"=>"JWT");
                    $payload = array("id"=>$row['IdUtilisateur'],
                                "username"=>$login, 
                                "roleUtilisateur"=>$row['RoleU'],
                                "exp"=>(time() + 86400));

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

    // Fermeture de la connexion
    $linkpdo = null;

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