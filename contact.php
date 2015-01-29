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
		<title><?php if (isset($_SESSION['login'])) { if($data2['etat']=='0') { echo '('.$nb_mp['nb_mp'].')'; } } ?> Contact</title>
	</head>

<body>

<?php include('header.php'); ?>

<div class="contenu">

	<h1 class="titre_contact">Contactez-nous</h1>
	<p  class="text_contact">Un bug? Une question? Une suggestion?</p>

		<form class="contact" method="post" action="contact.php">

			<input type="text" name="nom" class="nom" size="30" maxlength="200" placeholder="Nom" autofocus required />
			<input type="text" name="email" class="email" size="30" maxlength="200" placeholder="Email" required />
			<br />
			<textarea name="message" class="message" cols="74" rows="6" placeholder="Message" required></textarea>
			<br /><br />
			<input type="submit" name="submit_contact" class="submit" value="Envoyer" />

<?php
if(isset($_POST['submit_contact'])){
$dest    = 'angusalex92@gmail.com';
 
if(!$_POST['nom'] && $_POST['email'] && $_POST['message'])
    echo 'Vous avez oubli&eacute; votre e-mail';
elseif($_POST['nom'] && $_POST['email'] && $_POST['message']){
    $_POST   = array_map('htmlspecialchars', $_POST);
    $from    = 'From: '.$_POST['email']."\r\n";
    $objet   = 'Vous avez un nouveau message';
    if(!preg_match('!^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]{2,}\.[a-zA-Z]{2,4}$!',$_POST['email']))
        echo 'Votre e-mail n\'est pas valide';
    else{
        mail($dest, $_POST['nom'], $_POST['message'], $from);
        echo '<br />Message envoyé';
        }
    }
}
?>
	
		</form>

</div>

<?php include('footer.php'); ?>

</body>

</html>