<?php
function user_verified() {
  return isset($_SESSION['id_tchat']);
}



function parseText($content='') {
  $content = preg_replace('#(((https?://)|(w{3}\.))+[a-zA-Z0-9&;\#\.\?=_/-]+\.([a-z]{2,4})([a-zA-Z0-9&;\#\.\?=_/-]+))#i', '<a href="$0" target="_blank">$0</a>', $content);
  // Si on capte un lien tel que www.test.com, il faut rajouter le http://
  if(preg_match('#<a href="www\.(.+)" target="_blank">(.+)<\/a>#i', $content)) {
    $content = preg_replace('#<a href="www\.(.+)" target="_blank">(.+)<\/a>#i', '<a href="http://www.$1" target="_blank">www.$1</a>', $content);
    //preg_replace('#<a href="www\.(.+)">#i', '<a href="http://$0">$0</a>', $content);
  }

  // Insérez vos smiley ici, dans le premier tableau smiliesName
  // Et dans la colonne correpsondante du second tableau smiliesUrl
  // Indiquez le nom de l'image

  $smiliesName = array(':colere:', ':diable:', ':ange:', ':cool:', '&gt;_&lt;', ':kiss:', ':love:', ':honte:', ':\'\\(', ':waw:', ':\\)', ':D', ';\\)', ':p', ':lol:', ':euh:', ':\\(', ':o', ':colere2:', 'o_O', '\\^\\^', ':\\-@');
  $smiliesUrl  = array('angry.png', 'diable.png', 'ange.png', 'cool.png', 'pinch.png', 'kiss.png', 'love.png', 'rouge.png', 'pleure.png', 'huh.png', 'smiley.png', 'heureux.png', 'clin.png', 'langue.png', 'rire.gif', 'unsure.gif', 'pleure.png', 'huh.png', 'mechant.png', 'blink.gif', 'hihi.png', 'siffle.png');
  $smiliesPath = "smiley/";

  for ($i = 0, $c = count($smiliesName); $i < $c; $i++) {
    $content = preg_replace('`' . $smiliesName[$i] . '`isU', '<img style="position:relative;top:5px;" src="' . $smiliesPath . $smiliesUrl[$i] . '" alt="smiley" />', $content);
  }

  $content = stripslashes($content);
  return $content;
}
