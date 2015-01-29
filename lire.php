<?php
session_start();
require ('connect.php');
require ('connect2.php');
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
		<title><?php if (isset($_SESSION['login'])) { if($data2['etat']=='0') { echo '('.$nb_mp['nb_mp'].')'; } } ?> Messages</title>
	</head>

    <body>
	<?php include('header.php'); ?>
	
	<div class="contenu">
    <?php
    // on teste si notre paramètre existe bien et qu'il n'est pas vide
    if (!isset($_GET['id_expediteur']) || empty($_GET['id_expediteur'])) {
    header ('Location: messagerie');
    exit();
    }
    else {
    // on prépare une requete SQL selectionnant la date, le titre et l'expediteur du message que l'on souhaite lire, tout en prenant soin de vérifier que le message appartient bien au membre connecté
	$sql = 'SELECT DATE_FORMAT(date, \'%d/%m/%Y à %Hh%i\') AS date, message, id_expediteur, membre.login AS expediteur, messages.id as id_message, etat FROM messages, membre WHERE id_destinataire="'.$_SESSION['id'].'" AND id_expediteur=membre.id AND id_expediteur="'.$_GET['id_expediteur'].'" ORDER BY date';
	// on lance cette requete SQL à MySQL
    $req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
	$req2 = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
	$data2 = mysql_fetch_array($req2);
	?>
	<div class="envoyer_mp"><a class="envoyer_mp" href="messagerie">Retour à la messagerie</a></div><br />
	<?php
    $nb = mysql_num_rows($req);
    if ($nb == 0) {
    echo 'Aucun message reconnu.';
    }
    else {
	// si on a des messages, on affiche la date, un lien vers la page lire.php ainsi que le titre et l'auteur du message
    while ($data = mysql_fetch_array($req)) {
	?>
	<div class="news">
	<div class="block-forum" style="padding:20px 10px 0px 10px;">
	<?php
	mysql_query('UPDATE messages set etat="1" WHERE id_destinataire="'.$_SESSION['id'].'" AND id_expediteur="'.$data['id_expediteur'].'"');

	echo '' , stripslashes(htmlentities(trim($data['expediteur']))) , ' <span style="float:right;">'.$data['date'].' <a href="supprimer?id_message='.$data['id_message'].'&id_expediteur='.$data['id_expediteur'].'"><img style="vertical-align:middle;margin-top:-3px;" src="images/delete.png" /></a></span><br />', stripslashes(trim(nl2br($data['message']))), '<br /><br />';
	?>
	</div>
	</div>
	<?php
	}
    }
	}
	 // on teste si le formulaire a bien été soumis
    if (isset($_POST['go']) && $_POST['go'] == 'Envoyer') {
    if (empty($_POST['message'])) {
    $erreur = '<span style="color:red;">Au moins un des champs est vide</span>';
    }
	else {
	 // si tout a été bien rempli, on insère le message dans notre table SQL
    $sql = 'INSERT INTO messages VALUES("", "'.$_SESSION['id'].'", "'.$_POST['destinataire'].'", NOW(), "'.mysql_real_escape_string($_POST['message']).'", 0)';
    mysql_query($sql) or die('Erreur SQL !'.$sql.'<br />'.mysql_error());
	$success = '<span style="color:green;">Message envoyer</span>';
	}
	}
    ?>

<form class="contact" action="lire?id_expediteur=<?php echo $data2['id_expediteur']; ?>#ancre" method="post">
    <div id="ancre"></div>
	<input class="champ_pour_mp" name="destinataire" type="hidden" value="<?php echo '' ,$_GET['id_expediteur'], ''; ?>">
    <label>Message pour <?php echo ''.$data2['expediteur'].'' ?>:</label><textarea class="champ_message_mp" name="message" required></textarea><br />
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