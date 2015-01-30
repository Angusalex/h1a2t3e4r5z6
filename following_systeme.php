<?php
session_start();
require_once('connect.php');
if (isset($_GET['id_compte_a_follow']) && isset($_SESSION['id']))

{
  $ajout_follow = $bdd->prepare('INSERT INTO follows(id_follow, id_follower) VALUES(:id_follow, :id_follower)');
  $ajout_follow->execute(array(
    'id_follow' => $_GET['id_compte_a_follow'],
    'id_follower' => $_SESSION['id']
  ));
  $ajout_follow->closeCursor();
  header('Location: profil-public?id=' . $_GET['id_compte_a_follow'] . '');
}
else
{
  echo '<p>erreur de following<p>
  <p><a href="profil-public?id=' . $_GET['id_compte_a_follow'] . ' ">Retour � la page pr�cedente</a></p>' ;
}
