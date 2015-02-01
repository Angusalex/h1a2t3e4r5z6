<?php
function verif_follow($id_follow, $id_follower)
{
  $verification_follow = $bdd->prepare('SELECT id_follow, id_follower FROM follows WHERE id_follow = :id_follow_page AND id_follower = :id_follower_connecte ');
  $verification_follow->execute(array(
  'id_follow_page' => $id_follow,
  'id_follower_connecte' => $id_follower
  ));

  $verification_follow_ligne = $verification_follow->fetch();

  if(!empty($verification_follow_ligne))
  {
    return (true);
  }
  else
  {
    return (false);
  }

}
