<?php
session_start();
require ('connect.php');
?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8" />
    <meta name="description" content="Haterz" />
    <meta name="keywords" content="Haterz" />
    <meta name="author" content="Alexis Garnier" />
    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="style2.css" />
    <link href='http://fonts.googleapis.com/css?family=Oswald:400,700,300' rel='stylesheet' type='text/css'>
    <link rel="shortcut icon" href="images/icon.ico">
    <title><?php if (isset($_SESSION['login'])) { if($data2['etat']=='0') { echo '('.$nb_mp['nb_mp'].')'; } } ?> Haterz</title>
  </head>

<body>

<?php include('header.php'); ?>



<!-- Affichage des informations du profil -->
<?php
// cas où la page existe
if (isset($_GET['id']) && $_GET['id'] != 0)
{
$info_utilisateur = $bdd->prepare('SELECT login, date_format(date_creation , "%d/%m/%Y") AS date_compte , avatar FROM membre WHERE id = :id');
$info_utilisateur->execute(array(
  'id' => $_GET['id']
));

$ligne_info_user = $info_utilisateur->fetch();
//Affichage des info de l'utilisateur
?>
<div class="info-utilisateur-public">
  <img class="avatar-public" src="avatars/<?php echo $ligne_info_user['avatar'] ;?>"/>
  <h1><?php echo $ligne_info_user['login'] ; ?></h1>
  <p>Inscris depuis le <?php echo $ligne_info_user['date_compte'] ;?></p>
</div>
  <div>
    <div class="follow-du-profil">
      <h2 style="display:block;">Follows</h2>
      <?php
      $follows = $bdd->prepare('SELECT m.id id_follow, m.login nom_follow, m.avatar avatar_follow
      FROM membre m
      INNER JOIN follows f
      ON m.id = f.id_follow
      WHERE f.id_follower = :id
      ORDER BY m.login');
      $follows->execute(array(
        "id" => $_GET['id']
      ));
      while($follow=$follows->fetch())
      {
        ?>
        <img src="avatars/<?php echo $follow['avatar_follow']; ?>"/>
        <a style="color:#000" href="profil-public?id=<?php echo $follow['id_follow'] ;?>"><?php echo $follow['nom_follow'] ;?></a>
        <?php
      }
      $follows->closeCursor();
      ?>
    </div>
    <div class="followers-du-profil">
      <h2 style="display:block;">Followers</h2>
      <?php
      $followers = $bdd->prepare('SELECT m.id id_follower, m.login nom_follower, m.avatar avatar_follower
      FROM membre m
      INNER JOIN follows f
      ON m.id = f.id_follower
      WHERE f.id_follow = :id
      ORDER BY m.login');
      $followers->execute(array(
        "id" => $_GET['id']
      ));
      while($follower=$followers->fetch())
      {
        ?>
        <img src="avatars/<?php echo $follower['avatar_follower']; ?>"/>
        <a style="color:#000" href="profil-public?id=<?php echo $follower['id_follower'] ;?>"><?php echo $follower['nom_follower'] ;?></a>
        <?php
      }
      $followers->closeCursor();
      ?>
    </div>
  </div>
<?php
$info_utilisateur->closeCursor();
// bouton following proposé uniquement si l'utilisateur n'a pas déjà suivi le profil
  if (isset($_SESSION['id']))
  {
  $verification_follow = $bdd->prepare('SELECT id_follow, id_follower FROM follows WHERE id_follow = :id_follow_page AND id_follower = :id_follower_connecte ');
  $verification_follow->execute(array(
  'id_follow_page' => $_GET['id'],
  'id_follower_connecte' => $_SESSION['id']
  ));
  $verification_follow_ligne = $verification_follow->fetch();
    //bouton follow
    if ($_GET['id'] != $_SESSION['id'] )
    {
      if (!$verification_follow_ligne)
      {
      ?>
      <div class="zone-bouton-follow">
      <p><a href="following_systeme?id_compte_a_follow=<?php echo $_GET['id'] ;?>">Follow</a></p>
      </div>
      <?php
      }
      //bouton unfollow
      else
      {
      ?>
      <div class="zone-bouton-follow">
      <p><a href="unfollowing_systeme?id_compte_a_unfollow=<?php echo $_GET['id'] ;?>">Unfollow</a></p>
      </div>
      <?php
      }
    }
  $verification_follow->closeCursor();
  }

$publications_utilisateur = $bdd->prepare('SELECT id, pseudo, titre, contenu, DATE_FORMAT(date_creation, "%d/%m/%Y à %Hh%i") AS date_creation_fr FROM billets WHERE id_proprio = :id_user ORDER BY date_creation DESC');
$publications_utilisateur->execute(array(
  'id_user' => $_GET['id']
));
  //affichage des publication de l'utilisateur
  ?>
  <div class="news-profil">
  <?php

  while($publication = $publications_utilisateur->fetch())
  {
  ?>
    <div class="news">
      <div class="block-forum" style="margin-top:20px;padding:20px 10px 20px 10px;">
        <p><a class="titre_sujets" href="commentaires?billet=<?php echo $publication['id']; ?>"><?php echo nl2br(htmlspecialchars($publication['titre'])); ?></a></p>
        <p>le <?php echo $publication['date_creation_fr'] ?></p>
      </div>
    </div>
  <?php
  }
$publications_utilisateur->closeCursor();
  ?>
  </div>
  <?php

}
//cas ou l'utilisateur a été redirigé à partir d'une publication d'un utilisateur sans compte
else if (isset($_GET['id']) && $_GET['id'] == 0)
{
?>

<p>Il n'y a pas de profil pour cette personne</p>

<?php
}
//cas ou la page n'existe pas mauvaise valeur dans $_GET['id']
else
{
?>

<p>Désolé ce profil n'éxiste pas</p>

<?php
}
?>

<?php include('footer.php'); ?>

</body>

</html>
