<?php
    // ERREUR
        // V 405 : Cas par default : La méthode demandée n'est pas connue.
        // 404 : Cette ressource n'existe pas.
        // 400 : La syntaxe n'est pas correcte.
    // Problèmes :
        // V - DELETE ne devrait pas être par défaut 
        // V Gestion des erreurs 
        // POST & PUT doivent retourner la ressource entière
        // V PUT : l'id devrait être dans l'URL

    /// Paramétrage de l'entête HTTP (pour la réponse au Client)
    header("Content-Type:application/json");
    require_once("jwt_utils.php");

    // Connexion à la BDD
    $server = "127.0.0.1";; 
    $db = "ChuckNorris";
    $login = "root";
    $mdp = "";
    try{
        $linkpdo = new PDO("mysql:host=$server;dbname=$db;charset=UTF8",$login,$mdp);
    } catch (Exception $e){
        die('Erreur : '.$e->getMessage());
    }
    
    /// Identification du type de méthode HTTP envoyée par le client
    $http_method = $_SERVER['REQUEST_METHOD'];
    switch ($http_method){
        /// Cas de la méthode GET
        case "GET" :
            /// Récupération des critères de recherche envoyés par le Client
            $req = "SELECT * FROM Chuckn_Facts WHERE phrase IS NOT NULL";
            if (!empty($_GET['id'])){
                /// Traitement
                $req .= " AND id = :id";

                $res = $linkpdo->prepare($req);
                $res->bindParam(':id', $_GET['id'], PDO::PARAM_INT);                
            } else {
                $res = $linkpdo->prepare($req);
            }
            $res->execute();
            $matchingData = $res->fetchAll(PDO::FETCH_ASSOC);

            /// Envoi de la réponse au Client
            deliver_response(200, "Reponse de la requete GET", $matchingData);

            break;
            
        /// Cas de la méthode POST
        case "POST" :
            /// Récupération des données envoyées par le Client
            $postedData = file_get_contents('php://input');
            $postedData = json_decode($postedData, true, 512, JSON_THROW_ON_ERROR);

            if (!empty($postedData["phrase"])){
                /// Traitement
                $req = "INSERT INTO ChuckN_Facts (phrase,date_ajout) VALUES (?,NOW())";
                $res = $linkpdo->prepare($req);
                $res->execute(array($postedData["phrase"]));

                // Affichage de la ressource insérée
                $id = $linkpdo->lastInsertId();
                $res = $linkpdo->prepare("SELECT * FROM Chuckn_Facts 
                                            WHERE phrase IS NOT NULL 
                                            AND id = $id");
                $res->execute();
                $matchingData = $res->fetchAll(PDO::FETCH_ASSOC);
                    
                /// Envoi de la réponse au Client
                deliver_response(201, "Reponse de la requete POST", $matchingData);
            } else {
                /// Envoi de la réponse au Client
                deliver_response(400, "Erreur de syntaxe : veuillez spécifier la phrase à ajouter", NULL);
            }
            break;

        /// Cas de la méthode PUT
        case "PUT" :
            // Vérification de id dans l'URL
            if (!empty($_GET['id'])){
                /// Récupération des données envoyées par le Client
                $postedData = file_get_contents('php://input');
                $postedData = json_decode($postedData, true, 512, JSON_THROW_ON_ERROR);

                if (!empty($postedData["phrase"]) && !empty($postedData["vote"])
                        && !empty($postedData["faute"]) && !empty($postedData["signalement"]) 
                        && !empty($postedData["id"])){
                    // Traitement 
                    $res = $linkpdo->prepare("UPDATE ChuckN_Facts 
                    SET phrase = ?, 
                    vote = ?,
                    date_modif = NOW(),
                    faute = ?,
                    signalement = ?
                    WHERE id = ?");

                    $res->execute(array($postedData["phrase"],
                    $postedData["vote"],
                    $postedData["faute"],
                    $postedData["signalement"],
                    $postedData["id"]));
                    
                    // Affichage des données mis à jour
                    $res = $linkpdo->prepare("SELECT * FROM Chuckn_Facts 
                                        WHERE phrase IS NOT NULL 
                                        AND id = " . $postedData["id"]);
                    $res->execute();
                    $matchingData = $res->fetchAll(PDO::FETCH_ASSOC);
                    /// Envoi de la réponse au Client
                    deliver_response(200, "Reponse de la requete PUT", $matchingData);
                } else {
                    // Erreur de syntaxe
                    deliver_response(400, 
                    "Erreur de syntaxe : veuillez spécifier tous les champs dans le body (sauf la date d'ajout et la date de modification).", 
                    NULL);
                }
            } else {
                // Erreur de syntaxe
                deliver_response(400, 
                    "Erreur de syntaxe : veuillez spécifier l'identifiant de la ressource dans l'URL", 
                    NULL);
            }
            
            break;

        /// Cas de la méthode DELETE
        case "DELETE" :
            /// Récupération de l'identifiant de la ressource envoyé par le Client
            if (!empty($_GET['id'])){
                /// Traitement
                $res = $linkpdo->prepare("DELETE FROM ChuckN_Facts WHERE id = ?");
                $res->execute(array($_GET['id']));

                /// Envoi de la réponse au Client
                deliver_response(200, "Reponse de la requete DELETE", NULL);
            } else{
                // Erreur de syntaxe
                deliver_response(400, 
                    "Erreur de syntaxe : veuillez spécifier l'identifiant de la ressource dans l'URL.", 
                    NULL);
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