<?php
session_start();
require ('connect.php');
?>

<?php

if(isset($_GET['s'])) {
$req = $bdd->prepare('SELECT id, nom, createur_user, date FROM chat_salons WHERE id = ?');
$req->execute(array($_GET['s']));
$donnees = $req->fetch();
echo '<div style="padding:50px 50px;border:1px solid black;">'.$donnees['nom'].'</div>';
}
// Affichage de l'annonce //////////////////////////////////////////
$query = $bdd->query("SELECT * FROM chat_annonce LIMIT 0,1");
while ($data = $query->fetch())
	$json['annonce'] = utf8_encode($data['annonce_text']);
$query->closeCursor();
/* Si vous voulez faire appraître les messages depuis l'actualisation
de la page, laissez l'AVANT-DERNIERE ligne de la requete, sinon, supprimez-la */
$query = $bdd->prepare("
	SELECT message_id, message_user, message_time, message_text, account_id, account_login
	FROM chat_messages
	LEFT JOIN chat_accounts ON chat_accounts.account_id = chat_messages.message_user
	ORDER BY message_time ASC
");
$query->execute(array(
	'time' => $_GET['dateConnexion']
));

$count = $query->rowCount();
if($count != 0) {
	$json['messages'] = '<div id="messages_content">';
	// On crée un tableau qui continendra notre...tableau
	// Afin de placer les emssages en bas du chat
	// On triche un peu mais c'est plus simple :D
	$json['messages'] .= '<table ><tr><td style="height:400px;" valign="bottom">';
	$json['messages'] .= '<table style="width:100%">';

	$i = 1;
	$e = 0;
	$prev = 0;
	$text="";
	while ($data = $query->fetch()) {
		// Change la couleur dès que l'ID du membre est différent du précédent
		if($i != 1) {
			$idNew = $data['message_user'];		
			if($idNew != $id) {
				if($colId == 1) {
					$color = '#077692';
					$colId = 0;
				} else {
					$color = '#666';
					$colId = 1;
				}
				$id = $idNew;
			} else
				$color = $color;
		} else {
			$color = '#666';
			$id = $data['message_user'];
			$colId = 1;
		}


		$text .= '<div style="background-color: white;
				margin-top: 20px;
				padding: 20px 10px 20px 10px;
				border:  1px solid #e2e2e2;"><tr><td style="width:15%" valign="top">';
		
			// contenu du message	
			$text .= '<a href="#post" onclick="insertLogin(\''.addslashes($data['account_login']).'\')" style="color:black">';
			$text .= date('[H:i]', $data['message_time']);
			$text .= '&nbsp;<span style="color:'.$color.'">'.$data['account_login'].'</span>';
			$text .= '</a>';	

		$text .= '</td>';			
		$text .= '<td style="width:85%;padding-left:10px;" valign="top">: ';

			
		// On supprime les balises HTML
		$message = htmlspecialchars($data['message_text']); 

		// On transforme les liens en URLs cliquables
		$message = parseText($message);
			
		// Si le nom apparaît suivi de >, on le colore en orange
		if(user_verified()){
		if($_SESSION['login_tchat']==$data['account_login']){
			if(preg_match('#'.$_SESSION['login_tchat'].'&gt;#is', $message)) {
				$message = preg_replace('#'.$_SESSION['login_tchat'].'&gt;#is', '<b><span style="color:orange;">'.$_SESSION['login_tchat'].'&gt;</span></b>', $message);
			}
		}
	}
			
		// On ajoute le message en remplaçant les liens par des URLs cliquables
		$text .= $message.'<br />';
		$text .= '</td></tr></div>';

		$i++;
		$prev = $data['account_id'];
	}
		
	/* On crée la colonne messages dans le tableau json
	qui contient l'ensemble des messages */
	$json['messages'] = $text;

	$json['messages'] .= '</table>';
	$json['messages'] .= '</td></tr></table>';
	$json['messages'] .= '</div>';			
} else {
	$json['messages'] = 'Aucun message n\'a été envoyé pour le moment.';
}
$query->closeCursor();

// Encodage de la variable tableau json et affichage
echo json_encode($json);
?>