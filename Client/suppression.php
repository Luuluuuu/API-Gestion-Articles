<?php
    session_start();
    header("Location:index.php");
    require_once("lienAPI.php");
    $result = file_get_contents(
        API_APP . '?id=' . $_GET['id'], 
        false,
        stream_context_create(array('http' => array('method' => 'DELETE',
            'header' => array('Authorization: Bearer ' . $_SESSION["token"] . "\r\n")))) 
    );
    print_r($result);
?>