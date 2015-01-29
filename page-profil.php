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
    <!-- <link rel="stylesheet" href="bootstrap.css"/> -->
    <!--[if lt IE 9]-->
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <!-- [endif]-->
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="style2.css" />

    <link href='http://fonts.googleapis.com/css?family=Oswald:400,700,300' rel='stylesheet' type='text/css'>
    <link rel="shortcut icon" href="images/icon.ico">
    <title><?php if (isset($_SESSION['login'])) { if($data2['etat']=='0') { echo '('.$nb_mp['nb_mp'].')'; } } ?> Haterz</title>
  </head>

<body>

  <?php include('header.php'); ?>


  <!--Affichage des billets de l'utilisateur -->
  <?php
  $billets_profil = $bdd->prepare('SELECT pseudo, titre, contenu, DATE_FORMAT(date_creation, "%d/%m/%Y à %Hh%i" ) AS date FROM billets WHERE id_proprio = :id ');
  $billets_profil->execute(array(
  'id' => $_SESSION['id']
  ));
  while($billets = $billets_profil->fetch())
  {
  ?>
  <h1><?php echo $billets['titre'];?></h1>
  <p>Le <?php echo $billets['date']; ?></p>
  <p><?php echo $billets['contenu'];?></p>
  <p>par <?php echo $billets['pseudo'];?></p>
  <?php
  }
  ?>


</body>
</html>
