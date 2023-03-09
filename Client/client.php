<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <form action="publication.php" method="get">
        <label for="entree">Texte à ajouter : </label>
        <input type="text" name="entree" id="entree">
        <input type="submit" value="Ajouter" name="ajouter">
    </form>

    <table>
    <tr>
        <th>ID</th>
        <!-- <th>Auteur</th> --> <!-- à rajouter après-->
        <th>Publication</th>
        <th>Date d'ajout</th>
        <!-- <th>Like/Dislike</th> --> <!-- à rajouter après-->
        <th>Action</th>
    </tr>

    <?php
        $result = file_get_contents(
            'http://www.kilya.biz/api/chuckn_facts.php', // remplacer lien par le serveur
            false,
            stream_context_create(array('http' => array('method' => 'GET'))) // ou DELETE
        );

        $result = json_decode($result, true, 512, JSON_THROW_ON_ERROR);
        foreach ($result['data'] as $row) {
            if (strpos($row['phrase'],"<") === false) {
                echo '<tr><td>'. $row['id']. 
                // '</td><td>' . $row['auteur'] . à rajouter après
                '</td><td>' . $row['phrase'] . 
                '</td><td>' . $row['date_ajout'] .
                // '</td><td>' . $row['like'] . $row['dislike'] . à rajouter après
                '</td><td>  <a href="modification.php?id='. $row['id']. '">Modifier</a> <a href="deleteChucknFact.php?id='. $row['id']. '">Supprimer</a> </td></tr>';
            }     
        }
    ?>
    </table>
</body>
</html>