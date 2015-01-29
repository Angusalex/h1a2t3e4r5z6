<?php
require 'connect.php';
?>
<?php
// on teste si le visiteur a soumis le formulaire
if (isset($_POST['inscription']) && $_POST['inscription'] == 'Inscription') {
	// on teste l'existence de nos variables. On teste également si elles ne sont pas vides
	if ((isset($_POST['login']) && !empty($_POST['login'])) && (isset($_POST['pass']) && !empty($_POST['pass'])) && (isset($_POST['pass_confirm']) && !empty($_POST['pass_confirm'])) && (isset($_POST['pass_confirm']) && !empty($_POST['pass_confirm']))) {
	// on teste les deux mots de passe
	if ($_POST['pass'] != $_POST['pass_confirm']) {
		$erreur2 = 'Les 2 mots de passe sont différents';
}
// On vérifie que le formulaire n'a pas été soumis via une source externe
/*if($_SERVER["HTTP_REFERER"] !== "http://www.site.com/index.php") {
    echo "Le formulaire est soumis depuis une source externe !";
}*/

// On vérifie que tous les champs ont été complétés
else if(empty($_POST["email"]) || empty($_POST["login"])) {
    $erreur_inscription= "Vous devez compléter tous les champs du formulaire!";
}

// On vérifie que l'adresse de courriel soit valide
else if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    $erreur_inscription= "Votre adresse de courriel est incorrecte!";
}

// On vérifie que le Pseudo ne contient pas de caractères spéciaux
else if(!preg_match('/^[a-zA-Z0-9-_]+$/', $_POST["login"])) {
    $erreur_inscription= "Votre pseudo contient des caractères spéciaux!";
}

	else {
		// on recherche si ce login est déjà utilisé par un autre membre
		$req = $bdd->prepare('SELECT login FROM membre WHERE login = ?');
		$req->execute(array($_POST['login']));
		$data = $req->fetch();

		if (empty($data['login'])) {
		$req = $bdd->prepare('INSERT INTO membre (id, account_ip, login, pass_sha1, email, date_creation, avatar) 
		VALUES(:id, :ip, :login, :pass, :email, NOW(), :avatar)');
		$req->execute(array(
		'id' => '',
		'ip' => $_SERVER["REMOTE_ADDR"],
		'login' => $_POST['login'],
		'pass' => sha1($_POST['pass']),
		'email' => $_POST['email'],
		'avatar' => 'avatar.png',
		));

		$success_inscription = 'Inscription réussi<br />Vous pouvez maintenant vous connecter';
		}
		else {
		$erreur = 'Un membre possède déjà ce login';
		}
	}
	}
}

?>

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
		<title>S'inscrire à Haterz</title>
	</head>

<body>

<?php include('header.php'); ?>

<div class="contenu">

<form action="#" method="post" class="inscription">
<label for="pseudo">Pseudo:</label><input type="text" name="login" maxlength="12" class="input" value="" required><br /><br />
<label for="mdp">Mot de passe:</label><input type="password" name="pass" maxlength="32" class="input" value="" required><br /><br />
<label for="mdp2">Confirmation du mot de passe:</label><input type="password" maxlength="32" name="pass_confirm" class="input" value="" required><br /><br />
<label for="email">Email</label><input type="text" name="email" class="input" value="" required><br /><br />
<input class="submit" type="submit" name="inscription" value="Inscription" />
</form>

<div class="erreur">
<?php
if (isset($erreur)) echo '<br />',$erreur;
if (isset($erreur2)) echo '<br />',$erreur2;
if (isset($erreur_inscription)) echo '<br />',$erreur_inscription;
if (isset($success_inscription)) echo '<br /><span class="success_inscription">'.$success_inscription.'</span>';
?>
<div>

<div>

</body>

</html>