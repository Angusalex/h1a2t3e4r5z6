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
		<title>Haterz</title>
	</head>

<body>

<?php include('header.php'); ?>

<div class="contenu">

<div class="unlog">

	<form action="#" method="POST">
	<span>Nom du salon</span>
	<br><br>
				
	<center>
		<input type="text" name="post" id="pseudo_tchat" maxlength="16" placeholder="Nom du salon" required/><br /><br />
		<input type="submit" class="submit" value="Creer" />
	</center>
	</form>
	
</div>
<?php
if(!empty($_POST['post']))
{
	$insert = $bdd->prepare('
		INSERT INTO chat_salons (id, nom, createur_user, date) 
		VALUES(:id, :nom, :createur_user, NOW())
	');
	$insert->execute(array(
		'id' => '',
		'nom' => $_POST['post'],
		'createur_user' => $_SESSION['id_tchat'],
	));
	header('Location: tchat?s=1');
}
?>

</div>

<?php include('footer.php'); ?>

</body>

</html>