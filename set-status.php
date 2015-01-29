<?php
session_start();
require ('connect.php');
?>

<?php
if(user_verified()) {
	$insert = $bdd->prepare('
		UPDATE chat_online SET online_status = :status WHERE online_user = :user
	');
	$insert->execute(array(
		'status' => $_POST['status'],
		'user' => $_SESSION['id_tchat']		
	));
}
?>