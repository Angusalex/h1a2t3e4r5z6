<?php
if (isset($_SESSION['login']))
{
$req = $bdd->prepare('SELECT * FROM membre WHERE id= '.$_SESSION['id'].'');
$req->execute();
$data=$req->fetch(PDO::FETCH_OBJ);
}
?>

<header>
	
	<div id="conteneur_header">

		<div id="logo">
			<a title="Retour à l'acceuil" href="index">
			Haterz<span style="font-family:none;font-size:6pt;">BÊTA</span>
			</a>
		</div>
	
		<?php include('menu.php'); ?>
		
		<?php include('menufleche.php'); ?>
		
	</div>

</header>