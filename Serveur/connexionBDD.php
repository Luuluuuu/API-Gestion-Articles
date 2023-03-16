<?php
    function connexion(){
        // Connexion à la BDD
        $server = "127.0.0.1";; 
        $db = "api_gestion";
        $login = "root";
        $mdp = "";

        try{
            $linkpdo = new PDO("mysql:host=$server;dbname=$db;charset=UTF8",$login,$mdp);
        } catch (Exception $e){
            die('Erreur : '.$e->getMessage());
        }

        return $linkpdo;
    }
?>