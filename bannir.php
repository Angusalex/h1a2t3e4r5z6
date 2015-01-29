<?php
session_start();
require ('connect.php');
mysql_connect("localhost", "root", "");
mysql_select_db("mabase"); 
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
		<title>Haterz</title>
	</head>

<body>

<?php include('header.php'); ?>

<div class="contenu">

<?php
$isset = isset($_POST['ip']); //true si le formulaire est posté
$erreur = false; //On change cette valeur à la moindre erreur
if ($isset) {
     //Si le formulaire a été posté
     if (!empty($_POST['ip'])) {
          $ip = ip2long($_POST['ip']);
          if ($ip != false && $ip != -1) {
               //Si l'ip est valide
               if (!is_ban($ip)) {
                    mysql_query('INSERT INTO ban (`ban_ip`) VALUES(\'' . $ip . '\')');
                    $succes_ban='Cette adresse est désormais non-autorisée.';
               }
               else
                    $erreur_deja_ban='Cette adresse est déjà bannie.';
          }
          else 
               $erreur = 1; //L'ip est invalide, erreur #1
     }
     else
          $erreur = 0; //Le champ est vide, erreur #0
}
     //On affiche le formulaire
     print ('<form action="admin" method="post">
               <fieldset>
                    <legend>Bannir une adresse IP</legend>
                    <p><label for="ip">Adresse à bannir : </label></p>
                    <input type="text" name="ip" id="ip" />
                    <input type="submit" value="Bannir" />
               </fieldset>
             </form>');
	if(!$isset || $erreur !== false) $erreurs = array('Vous devez entrer une adresse ip.', 'L\'adresse ip est invalide.');
	if($erreur !== false) echo '<p style="color: red; font-weight: bold;">' . $erreurs[$erreur] . '</p>';
	if(isset($succes_ban)) echo '<p style="color: green; font-weight: bold;">' . $succes_ban . '</p>';
	if(isset( $erreur_deja_ban)) echo '<p style="color: red; font-weight: bold;">' . $erreur_deja_ban . '</p>';
	if(isset( $ip_no_ban)) echo '<p style="color: red; font-weight: bold;">' . $ip_no_ban . '</p>';
?>
<?php
$req = mysql_query("SELECT * FROM ban");
if($res = mysql_fetch_assoc($req)) {
        print('<ul>');
        do {
                print ('<li><a class="lien_page_forum2" href="admin?ip=' . $res['ban_ip'] . '" title="Débannir cette adresse">' . long2ip($res['ban_ip']) . '</a></li>');
        } while($res = mysql_fetch_assoc($req));

        print ('</ul>');
}
else{
        print mysql_error(); //Personne de banni, on le signale 
		}
?>
<?php
if (isset($_GET['ip'])) {
     mysql_query('DELETE FROM ban WHERE ban_ip=\'' . $_GET['ip'] . '\'');
     if (mysql_affected_rows() == 0)
          print ('<p style="color: red; font-weight: bold;">Cette adresse IP n\'était pas bannie.</p>');
     else
          print ('<p style="color: green; font-weight: bold;">L\'adresse IP a été débannie.</p>');
}
?>
</div>

<?php include('footer.php'); ?>

</body>

</html>