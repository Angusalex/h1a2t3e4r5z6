<?php
session_start();
require ('connect.php');
?>

<?php
if(!empty($_FILES['avatar']))
{
$avatar = $_FILES['avatar'];
$avatar_name = $avatar['name'];
$ext = strtolower(substr(strrchr($avatar_name,'.'),1));
$ext_aut = array('jpg','jpeg','png','gif');

function check_extension($ext,$ext_aut)
 {
   if(in_array($ext,$ext_aut))
   {
   return true;
   }
 }
 $valid = (!check_extension($ext,$ext_aut)) ? false : true;
 $erreur_avatar = (!check_extension($ext,$ext_aut)) ? 'Veuillez charger une image' : '';

 if($valid)
 {
 $max_size = 2100000;
 if($avatar['size']>$max_size)
 {
 $valid = false;
 $erreur_avatar = 'Fichier trop gros';
 }
 }

 if($valid)
 {
 if($avatar['error']>0)
 {
$valid = false;
$erreur_avatar = 'Erreur lors du transfert';
 }
 }
 if($valid)
 {
 $path_to_image = 'avatars/';
 $path_to_min = 'avatars/';

 $filename = $avatar_name;

$source = $avatar['tmp_name'];
$target = $path_to_image.$_SESSION['id'].'.'.$filename;

move_uploaded_file($source,$target);

if($ext == 'jpg' || $ext == 'jpeg') {$im = imagecreatefromjpeg($path_to_image.$_SESSION['id'].'.'.$filename);}
if($ext == 'png') {$im = imagecreatefrompng($path_to_image.$_SESSION['id'].'.'.$filename);}
if($ext == 'gif') {$im = imagecreatefromgif($path_to_image.$_SESSION['id'].'.'.$filename);}

$ox = imagesx($im);
$oy = imagesy($im);

$nx = 160;
$ny = 160;

$nm = imagecreatetruecolor($nx,$ny);

imagecopyresized($nm, $im, 0,0,0,0, $nx,$ny,$ox,$oy);

imagejpeg($nm, $path_to_min.$_SESSION['id'].'.'.$filename);

$nom_image = $filename;

$req = $bdd->prepare('UPDATE membre SET avatar= :avatar WHERE id= '.$_SESSION['id'].'');
$req->execute(array('avatar'=>$_SESSION['id'].'.'.$nom_image));
$req->closeCursor();
$success = 'Upload ok';
 }
}

$req = $bdd->prepare('SELECT * FROM membre WHERE id= '.$_SESSION['id'].'');
$req->execute();
?>

<?php
if (isset($_POST['new_login']))
{
// on recherche si ce login est déjà utilisé par un autre membre
$req = $bdd->prepare('SELECT login FROM membre WHERE login = ?');
$req->execute(array($_POST['new_login']));
$data = $req->fetch();
}

if (empty($data['login'])) {

if (!empty($_POST['new_login']))
{
if(preg_match('/^[a-zA-Z0-9-_]+$/', $_POST['new_login'])){
$req = $bdd->prepare('UPDATE membre SET login = :new_login WHERE id = '.$_SESSION['id'].'');
$req->execute(array('new_login' => htmlspecialchars(($_POST['new_login']))));
$req->closeCursor();
}
else if(!preg_match('/^[a-zA-Z0-9-_]+$/', $_POST['new_login'])) {
    $erreur_profil= "Votre pseudo contient des caractères spéciaux!";
}
}
if (!empty($_POST['new_pass']))
{
$req = $bdd->prepare('UPDATE membre SET pass_sha1 = :new_pass WHERE id = '.$_SESSION['id'].'');
$req->execute(array('new_pass' => htmlspecialchars(sha1($_POST['new_pass']))));
$req->closeCursor();
}
if (!empty($_POST['new_email']))
{
if(filter_var($_POST['new_email'], FILTER_VALIDATE_EMAIL)){
$req = $bdd->prepare('UPDATE membre SET email = :new_email WHERE id = '.$_SESSION['id'].'');
$req->execute(array('new_email' => htmlspecialchars($_POST['new_email'])));
$req->closeCursor();
}

else if(!filter_var($_POST['new_email'], FILTER_VALIDATE_EMAIL)) {
    $erreur_profil= "Votre adresse de courriel est incorrecte !";
}
}

$success_inscription = 'Inscription réussi<br />Vous pouvez maintenant vous connecter';
}
else {
$erreur_profil = 'Un membre possède déjà ce login';
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
		<title><?php if($data2['etat']=='0') { echo '('.$nb_mp['nb_mp'].')'; } ?> Modifier mon profil</title>
	</head>

<body>

<?php include('header.php'); ?>

<div class="contenu">

	<div id="avatar_profil">

<?php echo '<span><br />'.htmlspecialchars($data->login).'</span><br />';?>

<img class="avatar" src="avatars/<?php echo htmlspecialchars($data->avatar);?>" /><br /><br />

<form id="modif_avatar" method="post" action="#" enctype="multipart/form-data">
<input type="file" name="avatar" /><br /><br />
<input type="submit" name="avatar" class="submit" value="Envoyer" />

<?php
if (isset($erreur_avatar))
{
echo '<br />' .$erreur_avatar;
}
if (isset($success))
{
echo '<br />' .$success;
}
?>

</form>

</div>

		<div id="modif_profil">

<form action="#" method="post">
<input type="text" name="new_login" maxlength="12" class="input_profil" placeholder="Nouveau login"><br />
<input type="password" name="new_pass" maxlength="10" class="input_profil" placeholder="Nouveau mot de passe" autocomplete="off"><br />
<input type="password" name="confirm_new_pass" maxlength="10" class="input_profil" placeholder="Comfirmation" autocomplete="off"><br />
<input type="text" name="new_email" class="input_profil" placeholder="Nouveau email"><br />
<input type="submit" name="modifier" class="submit" value="Modifier">

<?php
if (isset($erreur_profil))
{
echo '<br />' .$erreur_profil;
}
?>

</form>

		</div>

	</div>

<?php include('footer.php'); ?>

</body>

</html>
