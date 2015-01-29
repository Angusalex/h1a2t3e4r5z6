<?php
session_start();
require ('connect.php');
require ('connect2.php');
require ('get_ip.php');
$ip = get_ip();
require ('is_ban.php');
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
		<title>Erreur 404</title>
	</head>

<body>

<?php include('header.php'); ?>

<div id="contenu_index">

<div class="index_erreur_404">

<p class="text_erreur_404">Erreur 404<br />
Page introuvable<br />
Quesque tu fais ici?! Oust!
</p>


<a class="retour_erreur" onclick="event.preventDefault();history.back();" href="#">Retournez à la page précédente</a>


</div>

</div>
<?php
 
$current_page = substr($_SERVER['REQUEST_URI'], strrpos($_SERVER['REQUEST_URI'], '/') + 1);
 
$_SESSION['last_page'] = $current_page;
 
// $_SESSION['last_page'] vaut index.php?page=accueil par exemple
 
?>
<?php include('footer.php'); ?>

</body>

</html>