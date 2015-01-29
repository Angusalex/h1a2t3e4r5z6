<?php
session_start();
require ('connect.php');

if(isset($_POST) && !empty($_POST['login']) && !empty($_POST['pass']))
{
extract($_POST);
// on recupère le password de la table qui correspond au login du visiteur

$req = $bdd->prepare('SELECT id, login, avatar FROM membre WHERE login = :login AND pass_sha1 = :pass_sha1 ');
$req->execute(array(
'login' => $_POST['login'],
'pass_sha1' => sha1($_POST['pass'])
));
$data=$req->fetch();

  if(!empty($data))
  {
  $_SESSION = array('id' => $data['id'], 'login' => $data['login'], 'avatars' => $data['avatar']);
  header('Location: index');
  exit();
  }
  else
  {
  $erreur = 'Pseudo ou mot de passe incorrect';
  include('connexion'); // On inclut le formulaire d'identification
  exit;
  }

}
else
{
	include('connexion'); // On inclut le formulaire d'identification
	exit;
}
