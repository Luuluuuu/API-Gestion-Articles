<?php

    header("Location:index.php");

    $result = file_get_contents(
        'http://www.kilya.biz/api/chuckn_facts.php?id='. $_GET['id'], // remplacer lien par le serveur
        false,
        stream_context_create(array('http' => array('method' => 'DELETE'))) // ou GET
    );
?>