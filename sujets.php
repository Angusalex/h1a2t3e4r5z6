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

if(isset($_GET['p']) && $_GET['p']>0 && $_GET['p']<=$nbPage){
	$cPage = $_GET['p'];
}
else{
	$cPage = 1;
}

$req = $bdd->query('SELECT id, pseudo, titre, contenu, id_proprio, avatar, DATE_FORMAT(date_creation, \'%d/%m/%Y à %Hh%i\') AS date_creation_fr FROM billets ORDER BY date_creation DESC LIMIT '.(($cPage-1)*$perPage).','.$perPage.'');
while ($donnees = $req->fetch())
{
?>

<div class="news">

	<a href="commentaires?billet=<?php echo $donnees['id']; ?>">

    <div class="block-forum" style="margin-top:20px;padding:20px 10px 20px 10px;">
    <?php
    // On affiche le contenu du billet
    echo nl2br(htmlspecialchars($donnees['titre']));
	?>
	<br />
	<p><span class="date_com">Par <strong><?php echo '<a href="index?id=' . $donnees['id_proprio'] . '">' . $donnees['pseudo'] . '</a>'; ?></strong> Le <?php echo $donnees['date_creation_fr']; ?>
	</span></p>
    <?php
	$requete = $bdd->prepare('SELECT COUNT(*) AS nb_messages FROM commentaires WHERE id_billet= '.$donnees['id'].'');
	$requete->execute();
	$donnees = $requete->fetch();
	?>
    <br />
	<em class="reponse_forum">Réponses: <?php echo $donnees['nb_messages']; ?></em>
    </div>
	</a>
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
if (isset($_SESSION['id']))
{
// on teste si le visiteur a soumis le formulaire
if (isset($_POST['titre']) AND $_POST['message'])
{
// On vérifie que le formulaire n'a pas été soumis via une source externe
/*if($_SERVER["HTTP_REFERER"] !== "http://www.site.com/index.php") {
    echo "Le formulaire est soumis depuis une source externe !";
}*/

// On vérifie que tous les champs ont été complétés
if (empty($_POST["titre"]) AND empty($_POST["message"])) {
    $erreur_inscription= "Vous devez compléter tous les champs!";
}

if (is_ban($ip)) {
    $erreur_ban='Vous êtes banni';
	 }

	else {

		$req = $bdd->prepare('INSERT INTO billets(pseudo, titre, contenu, avatar, id_proprio, date_creation) VALUES(:pseudo, :titre, :message, :avatar,  :id_proprio, NOW())');
		$req->execute(array(
		'pseudo' => $_SESSION['login'],
		'titre' => $_POST['titre'],
		'message' => $_POST['message'],
		'avatar' => "avatar.png",
		'id_proprio' => $_SESSION['id']
		));

		$success = 'Message envoyer';

		header('Refresh: 2; URL= sujets');

		}
	}
?>
<form action="sujets#ancre" method="post" class="sujets">
<label for="titre">Titre:</label><input type="text" name="titre" class="email_sujets" maxlength="200" required><br />
<label for="message">Message:</label><textarea type="text" name="message" class="message_sujets" required></textarea><br /><br />
<input class="submit" type="submit" name="post" value="Envoyer" />
<div id="ancre"></div>
</form>
<?php
}
elseif (empty($_SESSION['id']))
{
// on teste si le visiteur a soumis le formulaire
if (isset($_POST['titre']) AND $_POST['message'])
{
// On vérifie que le formulaire n'a pas été soumis via une source externe
/*if($_SERVER["HTTP_REFERER"] !== "http://www.site.com/index.php") {
    echo "Le formulaire est soumis depuis une source externe !";
}*/

// On vérifie que tous les champs ont été complétés
if (empty($_POST["titre"]) AND empty($_POST["message"])) {
    $erreur_inscription= "Vous devez compléter tous les champs!";
}

if (is_ban($ip)) {
    $erreur_ban='Vous êtes banni';
	 }

	else {

		$req = $bdd->prepare('INSERT INTO billets(pseudo, titre, contenu, avatar, date_creation) VALUES(:pseudo, :titre, :message, :avatar, NOW())');
		$req->execute(array(
		'pseudo' => $_POST['pseudo'],
		'titre' => $_POST['titre'],
		'message' => $_POST['message'],
		'avatar' => "avatar.png"
		));

		$success = 'Message envoyer';

		header('Refresh: 2; URL= sujets');

		}
	}
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
