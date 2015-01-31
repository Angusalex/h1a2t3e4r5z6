<?php
session_start();
require ('connect.php');
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
		<title><?php if (isset($_SESSION['login'])) { if($data2['etat']=='0') { echo '('.$nb_mp['nb_mp'].')'; } } ?> Commentaires</title>
	</head>

<body>

<?php include('header.php'); ?>

<div class="contenu">

<?php
$requete = $bdd->query('SELECT COUNT(id) AS nbArt FROM commentaires');
$donnees = $requete->fetch();

$nbArt = $donnees['nbArt'];
$perPage = 10;
$nbPage = ceil($nbArt/$perPage);

if(isset($_GET['p']) && $_GET['p']>0 && $_GET['p']<=$nbPage){
	$cPage = $_GET['p'];
}
else{
	$cPage = 1;
}
// Récupération du billet
$req = $bdd->prepare('SELECT id, pseudo, titre, contenu, avatar, id_proprio, DATE_FORMAT(date_creation, \'%d/%m/%Y à %Hh%i\') AS date_creation_fr FROM billets WHERE id = ?');
$req->execute(array($_GET['billet']));
$donnees = $req->fetch();
?>

<div class="news">
    <div class="block-forum" style="margin-top:20px;">
	<img class="avatar_forum" src="avatars/<?php echo $donnees['avatar'];?>" />
	<div style="margin-left:80px;margin-top:20px;">
	<?php echo nl2br(htmlspecialchars($donnees['contenu'])); ?>
	</div><br />
	<div class="date_com">Par <strong><?php echo '<a class="pseudo_sujets" href="profil-public?id=' . $donnees['id_proprio'] . '">' . htmlspecialchars($donnees['pseudo']) . '</a>'; ?></strong> Le <?php echo $donnees['date_creation_fr']; ?></div>
	</div>
	<div class="trait"></div>
<?php
$req->closeCursor(); // Important : on libère le curseur pour la prochaine requête

// Récupération des commentaires
$req = $bdd->prepare('SELECT auteur, commentaire, avatar, DATE_FORMAT(date_commentaire, \'%d/%m/%Y à %Hh%i\') AS date_commentaire_fr FROM commentaires WHERE id_billet = ? ORDER BY date_commentaire LIMIT '.(($cPage-1)*$perPage).','.$perPage.'');
$req->execute(array($_GET['billet']));

while ($donnees = $req->fetch())
{
?>

<div class="block-forum" style="margin-top:20px">
<img class="avatar_forum" src="avatars/<?php echo $donnees['avatar'];?>" />
<div style="margin-left:80px;margin-top:20px;">
<?php echo nl2br(htmlspecialchars($donnees['commentaire'])); ?>
</div><br />
<div class="date_com">Par <strong><?php echo htmlspecialchars($donnees['auteur']); ?></strong> Le <?php echo $donnees['date_commentaire_fr']; ?></div>
</div>

<?php
} // Fin de la boucle des commentaires
$req->closeCursor();
?>

<?php
$req = $bdd->prepare('SELECT id, pseudo, contenu, avatar, DATE_FORMAT(date_creation, \'%d/%m/%Y à %Hh%i\') AS date_creation_fr FROM billets WHERE id = ?');
$req->execute(array($_GET['billet']));
$donnees = $req->fetch();
?>
</div>
<div class='lien_page_forum'>
<?php
for($i=1;$i<=$nbPage;$i++){
	if($i==$cPage){
	echo "$i";
	}
	else{
	echo " <a class='lien_page_forum2' href=\"commentaires?billet=".$donnees['id']."&p=$i\">$i</a> ";
	}
	}
?>
</div>
<?php
if (isset($_SESSION['id']))
{
// on teste si le visiteur a soumis le formulaire
	if (isset($_POST['message']))
	{
// On vérifie que le formulaire n'a pas été soumis via une source externe
/*if($_SERVER["HTTP_REFERER"] !== "http://www.site.com/index.php") {
    echo "Le formulaire est soumis depuis une source externe !";
}*/

// On vérifie que tous les champs ont été complétés
		if (empty($_POST["message"]))
		{
	  $erreur3= "Vous devez compléter tous les champs!";
		}

		if (is_ban($ip))
		{
	  $erreur_ban='Vous êtes banni';
		}

		else
		{

		$req = $bdd->prepare('INSERT INTO commentaires(id_billet, auteur, commentaire, avatar, date_commentaire) VALUES(:id, :login, :message, :avatar, NOW())');
		$req->execute(array(
		'id' => $donnees['id'],
		'login' => $_SESSION['login'],
		'message' => $_POST['message'],
		'avatar' => $data->avatar
		));

		$success = 'Message envoyer';

		header('Refresh: 2; URL= commentaires?billet='.$donnees['id'].'');

		}
	}
?>

<form action="commentaires?billet=<?php echo $donnees['id']; ?>#ancre" method="post" class="sujets">
<label for="message">Message:</label><textarea type="text" name="message" class="message_sujets" required></textarea><br /><br />
<input class="submit" type="submit" name="post" value="Envoyer" />
<div id="ancre"></div>
</form>

<?php
}
elseif (empty($_SESSION['id']))
{
// on teste si le visiteur a soumis le formulaire
if (isset($_POST['pseudo']))
{
// On vérifie que le formulaire n'a pas été soumis via une source externe
/*if($_SERVER["HTTP_REFERER"] !== "http://www.site.com/index.php") {
    echo "Le formulaire est soumis depuis une source externe !";
}*/

// On vérifie que tous les champs ont été complétés
if (empty($_POST["pseudo"])) {
    $erreur3= "Vous devez compléter tous les champs!";
}

if (is_ban($ip)) {
    $erreur_ban='Vous êtes banni';
	 }

	else {

		$req = $bdd->prepare('INSERT INTO commentaires VALUES(:id, :id_billet, :pseudo, :message, :avatar, NOW())');
		$req->execute(array(
		'id'=>"",
		'id_billet'=> $donnees['id'],
		'pseudo' => $_POST['pseudo'],
		'message' => $_POST['message'],
		'avatar' => "avatar.png"
		));

		$success = 'Message envoyer';

		header('Refresh: 2; URL= commentaires?billet='.$donnees['id'].'');

		}
	}
?>
<form action="commentaires?billet=<?php echo $donnees['id']; ?>#ancre" method="post" class="sujets">
<label for="pseudo">Pseudo:</label><input type="text" name="pseudo" class="nom_sujets" maxlength="20" value="<?php if (isset($_POST['login'])) echo htmlentities(trim($_POST['login'])); ?>" required><br />
<label for="message">Message:</label><textarea type="text" name="message" class="message_sujets" required></textarea><br /><br />
<input class="submit" type="submit" name="post" value="Envoyer" />
<div id="ancre"></div>
</form>
<?php
}
?>

<div class="erreur">
<?php
if (isset($erreur)) echo '<br />',$erreur;
if (isset($erreur2)) echo '<br />',$erreur2;
if (isset($erreur3)) echo '<br />',$erreur3;
if (isset($success)) echo '<br />',$success;
if (isset($erreur_ban)) echo '<br />',$erreur_ban;
?>
</div>

</div>

<?php include('footer.php'); ?>

</body>

</html>
