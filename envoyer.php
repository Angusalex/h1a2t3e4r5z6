    <?php
    session_start();
	require ('connect.php');
    // on vérifie toujours qu'il s'agit d'un membre qui est connecté
    if (!isset($_SESSION['login'])) {
    // si ce n'est pas le cas, on le redirige vers l'accueil
    header ('Location: index');
    exit();
    }

    // on teste si le formulaire a bien été soumis
    if (isset($_POST['go']) && $_POST['go'] == 'Envoyer') {
    if (empty($_POST['destinataire']) || empty($_POST['message'])) {
    $erreur = 'Au moins un des champs est vide.';
    }
    else {
  $base = mysql_connect ('localhost', 'root', '');
    mysql_select_db ('mabase', $base);

    // si tout a été bien rempli, on insère le message dans notre table SQL
    $sql = 'INSERT INTO messages VALUES("", "'.$_SESSION['id'].'", "'.$_POST['destinataire'].'", "'.date("Y-m-d H:i:s").'", "'.mysql_real_escape_string($_POST['message']).'", 0)';
    mysql_query($sql) or die('Erreur SQL !'.$sql.'<br />'.mysql_error());

    mysql_close();

    header('Location: messagerie');
    exit();
    }
    }
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
		<title>Messages</title>
	</head>
	
	<?php include('header.php'); ?>
	
	<div class="contenu">
	
    <body>

    <?php
    $base = mysql_connect ('localhost', 'root', '');
    mysql_select_db ('mabase', $base);

    // on prépare une requete SQL selectionnant tous les login des membres du site en prenant soin de ne pas selectionner notre propre login, le tout, servant à alimenter le menu déroulant spécifiant le destinataire du message
    $sql = 'SELECT membre.login as nom_destinataire, membre.id as id_destinataire FROM membre WHERE id <> "'.$_SESSION['id'].'" ORDER BY login ASC';
    // on lance notre requete SQL
    $req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
    $nb = mysql_num_rows ($req);

    if ($nb == 0) {
    // si aucun membre n'a été trouvé, on affiche tout simplement aucun formulaire
    echo 'Vous êtes le seul membre inscrit.';
    }
    else {
    // si au moins un membre qui n'est pas nous même a été trouvé, on affiche le formulaire d'envoie de message
    ?>
	<h1 class="titre_contact">Envoyer un message:</h1>
	
    <form class="contact" action="envoyer" method="post">
    Pour: <select class="champ_pour_mp" name="destinataire">
    <?php
    // on alimente le menu déroulant avec les login des différents membres du site
    while ($data = mysql_fetch_array($req)) {
    echo '<option value="' , $data['id_destinataire'] , '">' , stripslashes(htmlentities(trim($data['nom_destinataire']))) , '</option>';
    }
    ?>
    </select><br />
    Message: <textarea class="champ_message_mp" name="message"><?php if (isset($_POST['message'])) echo stripslashes(htmlentities(trim($_POST['message']))); ?></textarea><br />
    <br />
	<input class="submit" type="submit" name="go" value="Envoyer">
    </form>
    <?php
    }
    mysql_free_result($req);
    mysql_close();
    ?>
    </select>
    <?php
    // si une erreur est survenue lors de la soumission du formulaire, on l'affiche
    if (isset($erreur)) echo '<br /><br />',$erreur;
    ?>
	
	</div>
	
	<?php include('footer.php'); ?>
	
    </body>
    </html>