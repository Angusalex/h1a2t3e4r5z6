<?php
session_start();
require ('connect.php');
mysql_connect("localhost", "root", "");
mysql_select_db("mabase"); 
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
		<title>Haterz</title>
	</head>

<body>

<?php include('header.php'); ?>

<div class="contenu">

<a style="color:black;" href="bannir.php">Bannir</a><br />

<a style="color:black;" href="admin_forum.php">Forum</a>

</div>

<?php include('footer.php'); ?>

</body>

</html>