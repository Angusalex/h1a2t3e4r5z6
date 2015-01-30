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


<div id="contenu_index">

<div id="index_2">

<p class="text_actu">Haterz<br />
Tout les sujets<br />
Liberte d'expression!
</p>

</div>

</div>


<?php include('footer.php'); ?>

</body>

</html>
