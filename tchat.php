<?php
session_start();
require ('connect.php');
require ('login_tchat.php');
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
		<meta name="viewport" content="width=device-width" />
		<!--[if lt IE 9]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<link href='http://fonts.googleapis.com/css?family=Oswald:400,700,300' rel='stylesheet' type='text/css'>
		<link rel="shortcut icon" href="images/icon.ico">
		<link rel="stylesheet" type="text/css" href="style.css">
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
		<script src="chat.js"></script>
		<title><?php if (isset($_SESSION['login'])) { if($data2['etat']=='0') { echo '('.$nb_mp['nb_mp'].')'; } } ?> Tchat</title>
	</head>

<body>

<?php include('header.php'); ?>

<div class="contenu">

	<div id="container">
<?php
if(isset($_SESSION['login'])) {

$login = $_SESSION['login'];
// On crée une requête pour rechercher un compte ayant pour nom $login
$query = $bdd->prepare("SELECT * FROM chat_accounts WHERE account_login = :login_tchat");
$query->execute(array(
	'login_tchat' => $login
));
// On compte le nombre d'entrées
$count=$query->rowCount();

// Si ce nombre est nul, alors on crée le compte, sinon on le connecte simplement
if($count == 0) {
	// Création du compte
	$insert = $bdd->prepare('
		INSERT INTO chat_accounts (account_id, account_ip, account_login)
		VALUES(:id_tchat, :ip, :login_tchat)
	');
	$insert->execute(array(
		'id_tchat' => '',
		'ip' => $_SERVER["REMOTE_ADDR"],
		'login_tchat' => htmlspecialchars($login),
	));

	/* Création d'une session id ayant pour valeur le dernier ID créé
	par la dernière requête SQL effectuée */
	$_SESSION['id_tchat'] = $bdd->lastInsertId();
	// On crée une session time qui prend la valeur de la date de connexion
	$_SESSION['time'] = time();
	$_SESSION['login_tchat'] = $login;
} else {
	$data = $query->fetch();


		$_SESSION['id_tchat'] = $data['account_id'];
		// On crée une session time qui prend la valeur de la date de connexion
		$_SESSION['time'] = time();
		$_SESSION['login_tchat'] = $data['account_login'];

}

// On termine la requête
$query->closeCursor();

}

 if(!user_verified()) {
if (is_ban($ip)) {
    $erreur_ban='Vous êtes banni';
	 }
	 else{
?>
<div class="unlog">
	<form action="#" method="POST">
	<span>Choisis un pseudo pour te connecter au chat!</span>
	<br><br>

	<center>
		<input type="text" name="login_tchat" id="pseudo_tchat" maxlength="16" placeholder="Pseudo" /><br /><br />
		<input type="submit" class="submit" value="Connexion" />
	</center>
	</form>
</div>
<?php
}
// permettra de créer l'utilisateur lors de la validation du formulaire
if(!empty($_POST['login_tchat']) AND !preg_match("#^[-. ]+$#", $_POST['login_tchat'])) {

/* On crée la variable login qui prend la valeur POST envoyée
car on va l'utiliser plusieurs fois */
$login = $_POST['login_tchat'].' Invité';

// On crée une requête pour rechercher un compte ayant pour nom $login
$query = $bdd->prepare("SELECT * FROM chat_accounts WHERE account_login = :login_tchat");
$query->execute(array(
	'login_tchat' => $login
));
// On compte le nombre d'entrées
$count=$query->rowCount();

// Si ce nombre est nul, alors on crée le compte, sinon on le connecte simplement
if($count == 0) {
	// Création du compte
	$insert = $bdd->prepare('
		INSERT INTO chat_accounts (account_id, account_ip, account_login)
		VALUES(:id_tchat, :ip, :login_tchat)
	');
	$insert->execute(array(
		'id_tchat' => '',
		'ip' => $_SERVER["REMOTE_ADDR"],
		'login_tchat' => htmlspecialchars($login),
	));

	/* Création d'une session id ayant pour valeur le dernier ID créé
	par la dernière requête SQL effectuée */
	$_SESSION['id_tchat'] = $bdd->lastInsertId();
	// On crée une session time qui prend la valeur de la date de connexion
	$_SESSION['time'] = time();
	$_SESSION['login_tchat'] = $login;
} else {
	$data = $query->fetch();


		$_SESSION['id_tchat'] = $data['account_id'];
		// On crée une session time qui prend la valeur de la date de connexion
		$_SESSION['time'] = time();
		$_SESSION['login_tchat'] = $data['account_login'];

}

// On termine la requête
$query->closeCursor();
header('Location: tchat');
}
}
if(!user_verified()) {
// On crée une requête pour rechercher un compte ayant pour nom $login
$query = $bdd->prepare("SELECT * FROM chat_accounts WHERE account_login = :login_tchat");

	$data = $query->fetch();

		$_SESSION['id_tchat'] = $data['account_id'];
		// On crée une session time qui prend la valeur de la date de connexion
		$_SESSION['time'] = time();
		$_SESSION['login_tchat'] = $data['account_login'];

// On termine la requête
$query->closeCursor();
}
else {
?>
	<div id="annonce"></div>
<?php
	}
	?>
	<input type="hidden" id="dateConnexion" value="<?php echo $_SESSION['time']; ?>" />
<?php
?>
</div>
  <!-- Statut //////////////////////////////////////////////////////// -->
	<table class="status"><tr>
		<td>
		<?php
	if(isset($_SESSION['login'])) {
$req2 = $bdd->prepare('SELECT * FROM membre WHERE login="'.($_SESSION['login']).'"');
	$req2->execute();
	$data=$req2->fetch();
	  $_SESSION2 = array('id' => $data['id'], 'login' => $data['login']);
	  }
if(empty($data['login'])) {
if(isset($_SESSION['login_tchat'])) {
echo '<a href="./invite_change_pseudo"><input type="button" value="Changer de pseudo" id="button_change_pseudo" /></a>';
}
}
?>
			<span id="statusResponse"></span>
			<select name="status" id="status" style="width:200px;" onchange="setStatus(this)">
				<option value="0">Absent</option>
				<option value="1">Occup&eacute;</option>
				<option value="2" selected>En ligne</option>
	</select>
	</td>
</tr></table>

	<table class="chat"><tr>
	<!-- zone des messages -->
	<?php
	if(isset($_SESSION['login'])) {
	?>
	<div class="salons">
	<div style="margin-left:3%;display:inline-block"><a href="new_salon" style="color:black;">Creer un salon</a></div>
	<?php

	$req = $bdd->prepare('SELECT * FROM chat_salons WHERE createur_user="2"');
	$req->execute();

	if(isset($data['id']))
	{
	while ($data = $req->fetch()) {
	echo '<div style="margin-left:10px;display:inline-block"><a href="tchat?s='.$data['id'].'" style="color:black;">'.$data['nom'].'</a></div>';
	}
	}

$req = $bdd->prepare('SELECT id, nom, createur_user, date FROM chat_salons WHERE id="'.$_GET['s'].'"');
$req->execute(array($_GET['s']));
$donnees = $req->fetch();
echo '<div style="padding:50px 50px;border:1px solid black;">'.$donnees['nom'].'</div>';

	}
	?>
	</div>
	<td valign="top" id="text-td">
		<div id="text">
			<div id="loading">
				<center>
				<span class="info" id="info">Chargement du chat en cours...</span><br />
				<img class="img_tchat" src="ajax-loader.gif" alt="patientez...">
				</center>
			</div>
		</div>
	</td>

	<!-- colonne avec les membres connectés au chat -->
	<td valign="top" id="users-td"><?php if (is_ban($ip)){
    $erreur_ban='Vous êtes banni';
	 }
	  else{
	 ?><div id="users">Chargement</div><?php
}
?></td>
</tr></table>

<!-- Zone de texte //////////////////////////////////////////////////////// -->
        <a name="post"></a>
	<table class="post_message"><tr>
		<td>
		<form action="" method="" onsubmit="postMessage(); return false;">
			<input type="text" id="message" maxlength="255" />
			<input type="button" onclick="postMessage()" value="Envoyer" id="post" />
		</form>
                <div id="responsePost" style="display:none"></div>
		</td>
	</tr></table>
</div>

<?php include('footer.php'); ?>

</body>

</html>
