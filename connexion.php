<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<meta name="author" content="Alexis Garnier" />
		<!--[if lt IE 9]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<link rel="stylesheet" href="style.css" />
		<link href='http://fonts.googleapis.com/css?family=Oswald:400,700,300' rel='stylesheet' type='text/css'>
		<link rel="shortcut icon" href="images/icon.ico">
		<title>Se connecter à Haterz</title>
	</head>

<body>

<?php include('header.php'); ?>

<div class="contenu">

<form action="login" method="post" class="connexion">
<label for="pseudo">Pseudo:</label><input type="text" name="login" maxlength="20" class="input" value="<?php if (isset($_POST['login'])) echo htmlentities(trim($_POST['login'])); ?>" required><br /><br />
<label for="mdp">Mot de passe:</label><input type="password" name="pass" maxlength="20" class="input" value="<?php if (isset($_POST['pass'])) echo htmlentities(trim($_POST['pass'])); ?>" required><br /><br />
<input class="submit" type="submit" name="inscription" value="Se connecter" />
</form>

<div class="erreur">
<?php
if (isset($erreur))
{
echo '<br />' .$erreur;
}
?>
</div>

</div>

</body>

</html>