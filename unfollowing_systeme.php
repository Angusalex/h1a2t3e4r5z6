<?php
session_start();
require_once('connect.php');
if (isset($_GET['id_compte_a_unfollow']) && isset($_SESSION['id']))

{
  $efface_follow = $bdd->prepare('DELETE FROM follows WHERE id_follow = :id_follow AND id_follower = :id_follower');
  $efface_follow->execute(array(
    'id_follow' => $_GET['id_compte_a_unfollow'],
    'id_follower' => $_SESSION['id']
  ));
  $efface_follow->closeCursor();
  header('Location: profil-public?id=' . $_GET['id_compte_a_unfollow'] . '');

}
