<?php

    header("Location:index.php");

    $result = file_get_contents(
        'http://localhost/API-Gestion-Articles/Serveur/apiApp.php?id='. $_GET['id'], // remplacer lien par le serveur
        false,
        stream_context_create(array('http' => array('method' => 'DELETE'))) // ou GET
    );
?>