<script type="text/javascript" src="functions.js"></script>

<?php
		if (isset($_SESSION['login']))
		{
?>
		<div class="profilco">
		<?php if($data2['etat']=='0') { echo '<span>'.$nb_mp['nb_mp'].'</span>'; } ?>
		<a href="messagerie.php"><img style="margin-right:10px;margin-top:-2px;vertical-align:middle;" src="images/message2.png"></a>
		<span><?php echo '<a href="page-profil?id=' . $_SESSION['id'] . '"/>'.$data->login.'</a>' ?></span>
		<img id="avatar" src="avatars/<?php echo ''.$data->avatar.'' ?>" />

		<div id="menufleche">
	<div class="menufleche" id="menu1" onclick="afficheMenu(this)">
		<a class="fleche" href="#"></a>
	</div>
	<div id="sousmenu1" style="display:none">
		<div class="sousmenu">
			<a href="profil">Modifier mon profil</a>
		</div>
		<div class="sousmenu">
			<a href="deconnexion">Déconnexion</a>
		</div>
	</div>
</div>
</div>
		<?php
		}

		else
		{
		echo
		'<div class="profildeco"><a href="inscription" class="inscrire">S\'inscrire</a>
		<span class="ou">ou</span>
		<a href="connexion" class="connecter">Se connecter</a></div>';
		}
		?>
