<?php
    /// Paramétrage de l'entête HTTP (pour la réponse au Client)
    session_start();
    header("Content-Type:application/json");

    // IMPORTS 
    require_once("jwt_utils.php");    
    require_once("connexionBDD.php");

    $linkpdo = connexion();

    if (get_bearer_token() != null) {
        $bearer_token = get_bearer_token();
        if (is_jwt_valid($bearer_token)){
            $payload = explode(".", $bearer_token); // Explosion du token
            $payloadDecode = json_decode(base64_decode($payload[1]), true, 512, JSON_THROW_ON_ERROR);
            $role = $payloadDecode["roleUtilisateur"];
            $idUtilisateur = $payloadDecode["id"];
        } else {
            deliver_response(401, "Authentification Error", NULL);

        }
    }
    
    /// Identification du type de méthode HTTP envoyée par le client
    $http_method = $_SERVER['REQUEST_METHOD'];
    switch ($http_method){
        /// Cas de la méthode GET
        case "GET" :
            if (!empty($role)){
                /// Récupération des critères de recherche envoyés par le Client
                $req = "SELECT infosArticle.*, COALESCE(likeDislike.Alike, 0) \"Like\", 
                COALESCE(likeDislike.ADislike, 0) Dislike 
                FROM (SELECT Utilisateur.NomUtilisateur Auteur, Article.*
                    FROM Article, Utilisateur            
                    WHERE Article.IdUtilisateur = Utilisateur.IdUtilisateur) infosArticle
                LEFT JOIN
                    (SELECT compteurLike.idArticle, ALike, ADislike 
                FROM (SELECT idArticle, SUM(ALike) ALike
                        FROM manipuler
                        GROUP BY idArticle) compteurLike
                    INNER JOIN
                        (SELECT idArticle, SUM(ADislike) ADislike
                        FROM manipuler
                        GROUP BY idArticle) compteurDislike
                        ON compteurLike.idArticle = compteurDislike.idArticle) likeDislike
                ON infosArticle.idArticle = likeDislike.idArticle
                ORDER BY infosArticle.DatePublication DESC";
                $res = $linkpdo->prepare($req);
                $res->execute();
                $matchingData = $res->fetchAll(PDO::FETCH_ASSOC);
                if ($role == "Moderator"){
                    foreach ($matchingData as $key=>$values){
                        // Récupère les utilisateurs ayant like l'article
                        $req = "SELECT NomUtilisateur 
                            FROM Manipuler, Utilisateur 
                            WHERE Utilisateur.IdUtilisateur = Manipuler.IdUtilisateur
                            AND ALike = 1
                            AND IdArticle = ?";
                        $res2 = $linkpdo->prepare($req);
                        $res2->execute(array($values["IdArticle"]));
                        $matchingData[$key]["Utlisateurs ayant like"] = array_values($res2->fetchAll(PDO::FETCH_COLUMN, 0));

                        // Récupère les utilisateurs ayant dislike l'article
                        $req = "SELECT NomUtilisateur 
                            FROM Manipuler, Utilisateur 
                            WHERE Utilisateur.IdUtilisateur = Manipuler.IdUtilisateur
                            AND ADislike = 1
                            AND IdArticle = ?";
                        $res2 = $linkpdo->prepare($req);
                        $res2->execute(array($values["IdArticle"]));
                        $matchingData[$key]["Utlisateurs ayant dislike"] = array_values($res2->fetchAll(PDO::FETCH_COLUMN, 0));
                    }
                }
            } else {
                $req = "SELECT Utilisateur.NomUtilisateur Auteur, Article.Contenu, Article.DatePublication
                            FROM Article, Utilisateur            
                            WHERE Article.IdUtilisateur = Utilisateur.IdUtilisateur";
                $res = $linkpdo->prepare($req);
                $res->execute();
                $matchingData = $res->fetchAll(PDO::FETCH_ASSOC);

            }
            /// Envoi de la réponse au Client
            deliver_response(200, "Status: OK", $matchingData);
            break;
            
        /// Cas de la méthode POST
        case "POST" :
            /// Récupération des données envoyées par le Client
            $postedData = file_get_contents('php://input');
            $postedData = json_decode($postedData, true, 512, JSON_THROW_ON_ERROR);

            if (!empty($postedData["contenu"]) && !empty($postedData["pseudo"])){
                // Traitement
                $pseudo = $postedData["pseudo"]; // Récupération du pseudo de l'utilisateur
                echo $pseudo;
                // Récupération de l'id de l'utilisateur
                $reqPseudo = "SELECT IdUtilisateur FROM Utilisateur WHERE NomUtilisateur = ?";             
                $resPseudo = $linkpdo->prepare($reqPseudo);
                $resPseudo->execute(array($pseudo));
                
                // Insertion du nouvel article
                $req = "INSERT INTO Article (Contenu, DatePublication, IdUtilisateur) VALUES (?,NOW(),?)";
                $res = $linkpdo->prepare($req);
                $res->execute(array($postedData["contenu"], $resPseudo->fetch()[0])); 

                // Affichage de la ressource insérée
                $id = $linkpdo->lastInsertId();
                $res = $linkpdo->prepare("SELECT * FROM Article 
                                            WHERE phrase IS NOT NULL 
                                            AND id = $id");
                $res->execute();
                $matchingData = $res->fetchAll(PDO::FETCH_ASSOC);
                    
                /// Envoi de la réponse au Client
                deliver_response(201, "Created", $matchingData);
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

                if (!empty($postedData["contenu"]) && !empty($postedData["pseudo"]) 
                        && !empty($postedData["id"])){
                    // Récupération de l'ID Utilisateur à partir d'un pseudo
                    $res = $linkpdo->prepare("SELECT IdUtilisateur FROM Utilisateur WHERE NomUtilisateur = ?");
                    $res->execute(array($postedData["pseudo"]));
                    $idUtilisateur = $res->fetch(PDO::FETCH_ASSOC);

                    // Traitement 
                    $res = $linkpdo->prepare("UPDATE Article 
                    SET DatePublication = NOW(),
                    Contenu = ?,
                    IdUtilisateur = ?
                    WHERE idArticle = ?");
                    $res->execute(array($postedData["contenu"],
                    $idUtilisateur["IdUtilisateur"],
                    $postedData["id"]));

                    // Affichage des données mis à jour
                    $res = $linkpdo->prepare("SELECT * FROM Article 
                                        WHERE contenu IS NOT NULL 
                                        AND idArticle = " . $postedData["id"]);
                    $res->execute();
                    $matchingData = $res->fetchAll(PDO::FETCH_ASSOC);

                    /// Envoi de la réponse au Client
                    deliver_response(200, "Status: OK", $matchingData);
                } else {
                    // Erreur de syntaxe
                    deliver_response(400, 
                    "Erreur de syntaxe : veuillez spécifier tous les champs dans le body (sauf la date d'ajout et la date de modification).", 
                    $postedData);
                }
            } else {
                // Erreur de syntaxe
                deliver_response(400, 
                    "Erreur de syntaxe : veuillez spécifier l'identifiant de la ressource dans l'URL", 
                    NULL);
            }
            
            break;

        // Cas de la méthode DELETE
        case "DELETE" :
            // Récupération de l'identifiant de la ressource envoyé par le Client
            if (!empty($_GET['id'])){
                if (!empty($role)){
                    // On récupère l'utilisateur de l'article
                    switch ($role){
                        case "Publisher":
                            $res = $linkpdo->prepare("SELECT IdUtilisateur FROM Article WHERE IdArticle = ?");
                            $res->execute(array($_GET['id']));
                            if ($res->rowCount()>0){
                                $colonneID = $res->fetch(PDO::FETCH_ASSOC);
                                $idUtilisateurArticle = $colonneID["IdUtilisateur"];
                                if ($idUtilisateur == $idUtilisateurArticle){
                                    // Suppression de l'article
                                    $res = $linkpdo->prepare("DELETE FROM Article WHERE idArticle = ?");
                                    $res->execute(array($_GET['id']));
                                    // Envoi de la réponse au Client
                                    deliver_response(200, "Statut : OK", NULL);

                                } else {deliver_response(403, "Erreur d'autorisation", NULL);}
                            } else {deliver_response(404, "Erreur de syntaxe : Article introuvable", NULL);}
                            break;
                        case "Moderator":
                            // Suppression de l'article
                            $res = $linkpdo->prepare("DELETE FROM Article WHERE idArticle = ?");
                            $res->execute(array($_GET['id']));
                            // Envoi de la réponse au Client
                            deliver_response(200, "Statut : OK", NULL);
                            break;
                    }
                }  else {deliver_response(403, "Erreur d'autorisation", NULL);}                
            } else{
                deliver_response(400, "Erreur de syntaxe : ID de l'article introuvable", NULL);
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