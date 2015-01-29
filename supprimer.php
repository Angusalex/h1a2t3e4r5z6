    <?php
    session_start();
	require ('connect.php');
    // on vérifie toujours qu'il s'agit d'un membre qui est connecté
    if (!isset($_SESSION['login'])) {
    // si ce n'est pas le cas, on le redirige vers l'accueil
    header ('Location: index');
    exit();
    }

    // on teste si l'id du message a bien été fourni en argument au script envoyer.php
    if (!isset($_GET['id_message']) || empty($_GET['id_message'])) {
    header ('Location: messagerie');
    exit();
    }
    else {
    // on prépare une requête SQL permettant de supprimer le message tout en vérifiant qu'il appartient bien au membre qui essaye de le supprimer
    $req = $bdd->prepare('DELETE FROM messages WHERE id_destinataire=:id_destinataire AND id=:id OR id_expediteur=:id_expediteur');
    $req->execute(array(
        'id_destinataire' => $_SESSION['id'],
        'id' => $_GET['id_message'],
        'id_expediteur' => $_GET['id_message']
        ));
    $req->closeCursor();
	
	if (isset($_GET['id_expediteur']) AND isset($_GET['id_message'])) {
    header ('Location: lire?id_expediteur='.$_GET['id_expediteur'].'');
	}
	
	elseif (isset($_GET['id_message'])) {
    header ('Location: messagerie?id_expediteur='.$_GET['id_expediteur'].'');
	}
	
    exit();
    }
    ?>