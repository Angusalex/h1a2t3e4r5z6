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
    if (empty($_POST["titre"]) AND empty($_POST["message"]))
    {
        $erreur_inscription= "Vous devez compléter tous les champs!";
    }

    if (is_ban($ip))
    {
        $erreur_ban='Vous êtes banni';
    }

    else
    {

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
}
else
{
  // on teste si le visiteur a soumis le formulaire
  if (isset($_POST['titre']) AND $_POST['message'])
  {
  // On vérifie que le formulaire n'a pas été soumis via une source externe
  /*if($_SERVER["HTTP_REFERER"] !== "http://www.site.com/index.php") {
      echo "Le formulaire est soumis depuis une source externe !";
  }*/

    // On vérifie que tous les champs ont été complétés
    if (empty($_POST["titre"]) AND empty($_POST["message"]))
    {
    $erreur_inscription= "Vous devez compléter tous les champs!";
    }

    if (is_ban($ip))
    {
        $erreur_ban='Vous êtes banni';
    }

    else
    {

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
}
