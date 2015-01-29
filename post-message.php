<?php
session_start();
require ('connect.php');
require ('get_ip.php');
$ip = get_ip();
require ('is_ban.php');
?>
<?php
if (is_ban($ip)) {
$erreur_ban='Vous êtes banni';
	}
	 
elseif(isset($_SESSION['login_tchat'])) {

	if(isset($_POST['message']) AND !empty($_POST['message'])) {
		/* On teste si le message ne contient qu'un ou plusieurs points et
		qu'un ou plusieurs espaces, ou s'il est vide. 
			^ -> début de la chaine - $ -> fin de la chaine
			[-. ] -> espace, rien ou point 
			+ -> une ou plusieurs fois
		Si c'est le cas, alors on envoie pas le message */
		if(!preg_match("#^[-. ]+$#", $_POST['message'])) {
			$query = $bdd->prepare("SELECT * FROM chat_messages WHERE message_user = :user ORDER BY message_time DESC LIMIT 0,1");
			$query->execute(array(
				'user' => $_SESSION['id_tchat']
			));
			$count = $query->rowCount();
			$data = $query->fetch();
			// Vérification de la similitude
			if($count != 0) 
				similar_text($data['message_text'], $_POST['message'], $percent);

			if($percent < 80) {
				// Vérification de la date du dernier message.
				if(time()-5 >= $data['message_time']) {

$insert = $bdd->prepare('
	INSERT INTO chat_messages (message_id, message_user, message_time, message_text, id_salon) 
	VALUES(:id, :user, :time, :text, :idd)
');

$insert->execute(array(
	'id' => '',
	'user' => $_SESSION['id_tchat'],
	'time' => time(),
	'text' => $_POST['message'],
	'idd' => $_GET['s'],
));
echo true;
				
					} else {
						echo 'Votre dernier message est trop récent. Baissez le rythme :D';}
				} else {
					echo 'Votre dernier message est très similaire.';}
			} else {
				echo 'Votre message est vide.';}
		} else {
			echo 'Votre message est vide.';}
	} else {
		echo 'Vous devez être connecté.';}
		
if(isset($erreur_ban)) {echo '<br />',$erreur_ban;}
?>