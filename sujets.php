<?php
session_start();
require ('connect.php');
require ('get_ip.php');
$ip = get_ip();
require ('is_ban.php');
require('ajout_sujet.php');
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
		<title><?php if (isset($_SESSION['login'])) { if($data2['etat']=='0') { echo '('.$nb_mp['nb_mp'].')'; } } ?> Sujets</title>
	</head>

<body>

<?php include('header.php'); ?>

<div class="contenu">

<?php
$requete = $bdd->query('SELECT COUNT(id) AS nbArt FROM billets');
$donnees = $requete->fetch();

$nbArt = $donnees['nbArt'];
$perPage = 10;
$nbPage = ceil($nbArt/$perPage);

if(isset($_GET['p']) && $_GET['p']>0 && $_GET['p']<=$nbPage)
{
	$cPage = $_GET['p'];
}
else
{
	$cPage = 1;
}

$req = $bdd->query('SELECT id, pseudo, titre, contenu, id_proprio, avatar, DATE_FORMAT(date_creation, \'%d/%m/%Y à %Hh%i\') AS date_creation_fr FROM billets ORDER BY date_creation DESC LIMIT '.(($cPage-1)*$perPage).','.$perPage.'');
while ($donnees = $req->fetch())
{
?>

<div class="news">
  <div class="block-forum" style="margin-top:20px;padding:20px 10px 20px 10px;">
  <?php
  // On affiche le contenu du billet
  echo '<a class="titre_sujets" href="commentaires?billet='.$donnees['id'].'">'.nl2br(htmlspecialchars($donnees['titre'])).'</a>';
	?>
	<br />
	<p><span class="date_com">Par <strong><?php echo '<a class="pseudo_sujets" href="profil-public?id=' . $donnees['id_proprio'] . '">' . htmlspecialchars($donnees['pseudo']) . '</a>'; ?></strong> Le <?php echo $donnees['date_creation_fr']; ?>
	</span></p>
    <?php
	$requete = $bdd->prepare('SELECT COUNT(*) AS nb_messages FROM commentaires WHERE id_billet= '.$donnees['id'].'');
	$requete->execute();
	$donnees = $requete->fetch();
	?>
	<em class="reponse_forum">Réponses: <?php echo $donnees['nb_messages']; ?></em>
  </div>
</div>

<?php
} // Fin de la boucle des billets
$req->closeCursor();
?>
<div class='lien_page_forum'>
<?php
for($i=1;$i<=$nbPage;$i++){
	if($i==$cPage){
	echo "$i";
	}
	else{
	echo " <a class='lien_page_forum2' href=\"sujets?p=$i\">$i</a> ";
	}
	}
?>
</div>
<?php
//Si l'utilisateur est connécté
if (isset($_SESSION['id']))
{
?>
<form action="sujets#ancre" method="post" class="sujets">
<label for="titre">Titre:</label><input type="text" name="titre" class="email_sujets" maxlength="200" required><br />
<label for="message">Message:</label><textarea type="text" name="message" class="message_sujets" required></textarea><br /><br />
<input class="submit" type="submit" name="post" value="Envoyer" />
<div id="ancre"></div>
</form>
<?php
}
// utilisateur non connécté
elseif (empty($_SESSION['id']))
{
?>
<form action="sujets#ancre" method="post" class="sujets">
<label for="pseudo">Pseudo:</label><input type="text" name="pseudo" class="nom_sujets" maxlength="20" value="<?php if (isset($_POST['login'])) echo htmlentities(trim($_POST['login'])); ?>" required><br />
<label for="titre">Titre:</label><input type="text" name="titre" class="email_sujets" maxlength="200" required><br />
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
