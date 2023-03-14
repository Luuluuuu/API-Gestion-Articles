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
        <th>Auteur</th>
        <th>Contenu</th>
        <th>Date d'ajout</th>
        <th>Like</th>
        <th>Dislike</th>
        <th>Action</th>
    </tr>

    <?php
        $result = file_get_contents(
            'http://localhost/API-Gestion-Articles/Serveur/apiApp.php', // remplacer lien par le serveur
            false,
            stream_context_create(array('http' => array('method' => 'GET'))) // ou DELETE
        );

        $result = json_decode($result, true, 512, JSON_THROW_ON_ERROR);
        foreach ($result['data'] as $row) {
            if (strpos($row['Contenu'],"<") === false) {
                echo '</td><td>' . $row['NomUtilisateur'] . 
                '</td><td>' . $row['Contenu'] . 
                '</td><td>' . $row['DatePublication'] .
                // '</td><td>' . $row['like'] . $row['dislike'] . à rajouter après
                '</td><td>  <a href="modification.php?id='. $row['IdArticle']. '">Modifier</a> <a href="deleteChucknFact.php?id='. $row['IdArticle']. '">Supprimer</a> </td></tr>';
            }     
        }
    ?>
    </table>
</body>
</html>