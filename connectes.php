<?php
// -------
// ÉTAPE 1 : on vérifie si l'IP se trouve déjà dans la table.
// Pour faire ça, on n'a qu'à compter le nombre d'entrées dont le champ "ip" est l'adresse IP du visiteur.
$retour = $bdd->query('SELECT COUNT(*) AS nbre_entrees FROM connectes WHERE ip=\'' . $_SERVER['REMOTE_ADDR'] . '\'');
$donnees2 = $retour->fetch();

if ($donnees2['nbre_entrees'] == 0) // L'IP ne se trouve pas dans la table, on va l'ajouter.
{
    $retour = $bdd->query ('INSERT INTO connectes VALUES(\'' . $_SERVER['REMOTE_ADDR'] . '\', ' . time() . ')');
}
else // L'IP se trouve déjà dans la table, on met juste à jour le timestamp.
{
    $retour = $bdd->query ('UPDATE connectes SET timestamp=' . time() . ' WHERE ip=\'' . $_SERVER['REMOTE_ADDR'] . '\'');
}

// -------
// ÉTAPE 2 : on supprime toutes les entrées dont le timestamp est plus vieux que 5 minutes.

// On stocke dans une variable le timestamp qu'il était il y a 5 minutes :
$timestamp_5min = time() - (60 * 5); // 60 * 5 = nombre de secondes écoulées en 5 minutes
$retour = $bdd->query ('DELETE FROM connectes WHERE timestamp < ' . $timestamp_5min);

// -------
// ÉTAPE 3 : on compte le nombre d'IP stockées dans la table. C'est le nombre de visiteurs connectés.
$retour = $bdd->query('SELECT COUNT(*) AS nbre_entrees FROM connectes');
$donnees2 = $retour->fetch();


// Ouf ! On n'a plus qu'à afficher le nombre de connectés !
if ($donnees2['nbre_entrees'] == 1)
{
echo '<p class="user_online"><img src="user_online.png" alt="user_online" style="vertical-align:-4px;" />' . $donnees2['nbre_entrees'] . ' visiteur en ligne</p>';
}
else
{
echo '<p class="user_online"><img src="user_online.png" alt="user_online" style="vertical-align:-4px;" />' . $donnees2['nbre_entrees'] . ' visiteurs en ligne</p>';
}
?>