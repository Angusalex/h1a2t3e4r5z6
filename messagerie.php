<?php
    session_start();
	require ('connect.php');
    // on vérifie toujours qu'il s'agit d'un membre qui est connecté
    if (!isset($_SESSION['login'])) {
    // si ce n'est pas le cas, on le redirige vers l'accueil
    header ('Location: index.php');
    exit();
    }
?>
<?php
	$sql = 'SELECT date, membre.login as expediteur, messages.id as id_message, id_expediteur, message, MIN(etat) AS etat FROM messages, membre WHERE id_destinataire="'.$_SESSION['id'].'" AND id_expediteur=membre.id GROUP BY id_expediteur ORDER BY date DESC';
    // lancement de la requete SQL
    $req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
	$data2 = mysql_fetch_array($req);
	$req_mp = $bdd->query('SELECT COUNT(*) AS nb_mp FROM messages WHERE id_destinataire="'.$_SESSION['id'].'" AND etat="0"');
	$nb_mp = $req_mp->fetch();
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
		<link rel="stylesheet" href="style.css" />
		<link href='http://fonts.googleapis.com/css?family=Oswald:400,700,300' rel='stylesheet' type='text/css'>
		<link rel="shortcut icon" href="images/icon.ico">
		<script>function hide()
{
document.getElementById('to_hide').style.display = 'none';
}</script>
		<title><?php if($data2['etat']=='0') { echo '('.$nb_mp['nb_mp'].')'; } ?> Messagerie</title>
	</head>

    <body>
	<?php include('header.php'); ?>
	
	<div class="contenu">
	<?php
    // on prépare une requete SQL cherchant tous les titres, les dates ainsi que l'auteur des messages pour le membre connecté
    $sql = 'SELECT DATE_FORMAT(MAX(date), \'%d/%m/%Y à %Hh%i\') AS date, membre.login as expediteur, messages.id as id_message, id_expediteur, MIN(etat) AS etat FROM messages, membre WHERE id_destinataire="'.$_SESSION['id'].'" AND id_expediteur=membre.id GROUP BY id_expediteur ORDER BY date DESC';
    // lancement de la requete SQL
    $req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
    $nb = mysql_num_rows($req);
    if ($nb == 0) {
    echo '<div style="margin-top:180px;text-align:center;">Vous n\'avez aucun message</div>';
    }
    else {

	?>
	<div class="news" style="margin-top:150px;">
	<?php
    // si on a des messages, on affiche la date, un lien vers la page lire.php ainsi que le titre et l'auteur du message
    while ($data = mysql_fetch_array($req))	{
	?>
	<a href="lire?id_expediteur=<?php echo $data['id_expediteur']; ?>">
	<div class="block-forum" style="padding:20px 10px 0px 10px;">
	<?php
    if($data['etat']=='0')
	{
	echo '<img style="vertical-align:middle;margin-top:-4px;" src="images/msgnew.png" alt="msgnew" /> ';
	}
	else
	{
	echo '<img style="vertical-align:middle;margin-top:-8px;" src="images/msgread.png" alt="msgread" /> ';
	}
	echo '' , stripslashes(htmlentities(trim($data['expediteur']))) , '<span style="float:right;margin-right:18px;">'.$data['date'].'</span>', '<br /><br />';
	?>
	</div>
	<a style="position:relative;top:-47px;left:795px;" href="supprimer?id_message=<?php echo $data['id_expediteur']; ?>"><img style="vertical-align:middle;margin-top:-3px;" src="images/delete.png" /></a>
	</a>
	<?php
	}
	?>
	</div>
	<?php
    }
	 // on teste si le formulaire a bien été soumis
    if (isset($_POST['go']) && $_POST['go'] == 'Envoyer') {
    if (empty($_POST['destinataire']) || empty($_POST['message'])) {
    $erreur = '<span style="color:red;">Au moins un des champs est vide</span>';
    }
	else {
	 // si tout a été bien rempli, on insère le message dans notre table SQL
    $sql = 'INSERT INTO messages VALUES("", "'.$_SESSION['id'].'", "'.$_POST['destinataire'].'", NOW(), "'.mysql_real_escape_string($_POST['message']).'", 0)';
    mysql_query($sql) or die('Erreur SQL !'.$sql.'<br />'.mysql_error());
	$success = '<span style="color:green;">Message envoyer</span>';
	}
	}
	 // on prépare une requete SQL selectionnant tous les login des membres du site en prenant soin de ne pas selectionner notre propre login, le tout, servant à alimenter le menu déroulant spécifiant le destinataire du message
    $sql = 'SELECT membre.login as nom_destinataire, membre.id as id_destinataire FROM membre WHERE id <> "'.$_SESSION['id'].'" ORDER BY login ASC';
    // on lance notre requete SQL
    $req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
	?>

<form class="contact" action="messagerie#ancre" method="post">
	<div id="ancre"></div>
    Pour: <select class="champ_pour_mp" name="destinataire" onfocus="hide()">
	      <option id="to_hide"></option>
    <?php
    // on alimente le menu déroulant avec les login des différents membres du site
    while ($data = mysql_fetch_array($req)) {
    echo '<option value="' , $data['id_destinataire'] , '">' , stripslashes(htmlentities(trim($data['nom_destinataire']))) , '</option>';
    }
    ?>
    </select><br />
    Message: <textarea class="champ_message_mp" name="message" required></textarea><br />
    <br />
	<input class="submit" type="submit" name="go" value="Envoyer">
</form>
<div class="erreur">
	<?php
	if (isset($success)) echo '<br />',$success;
    if (isset($erreur)) echo '<br /><br />',$erreur;
	?>
</div>
	</div>
	
	<?php
    mysql_free_result($req);
    mysql_close();
	?>
	
	<?php include('footer.php'); ?>
	
	</body>
</html>