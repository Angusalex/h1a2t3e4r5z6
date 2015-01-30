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
    <link href='http://fonts.googleapis.com/css?family=Oswald:400,700,300' rel='stylesheet' type='text/css'>
    <link rel="shortcut icon" href="images/icon.ico">
    <title><?php if (isset($_SESSION['login'])) { if($data2['etat']=='0') { echo '('.$nb_mp['nb_mp'].')'; } } ?> Haterz</title>
  </head>

<body>

<?php include('header.php'); ?>



<!-- Affichage des informations du profil -->
<?php
if (isset($_GET['id']) && $_GET['id'] != 0)
{
?>
<p><?php echo $_GET['id'] ;?> </p>
<?php
}

else if (isset($_GET['id']) && $_GET['id'] == 0)
{
?>
<p>Il n'y a pas de profil pour cette personne</p>
<?php
}

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
