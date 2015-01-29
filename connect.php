<!-- Connection à la bdd crypté -->
<?php
$bdd = new PDO('mysql:host=localhost;dbname=mabase;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));



if (isset($_SESSION['login'])) {
$sql = $bdd->query('SELECT date, membre.login as expediteur, messages.id as id_message, id_expediteur, message, MIN(etat) AS etat FROM messages, membre WHERE id_destinataire="'.$_SESSION['id'].'" AND id_expediteur=membre.id GROUP BY id_expediteur ORDER BY date DESC');
$data2 = $sql->fetch();
$req_mp = $bdd->query('SELECT COUNT(*) AS nb_mp FROM messages WHERE id_destinataire="'.$_SESSION['id'].'" AND etat="0"');
$nb_mp = $req_mp->fetch();
}
