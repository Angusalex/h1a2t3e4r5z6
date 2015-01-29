<?php
//Indique si une adresse est bannie
function is_ban($ip) {
     $ip = ip2long($ip);
     $query = $bdd->prepare('SELECT * FROM ban WHERE ban_ip=:ip');
     $query->execute(array(
	'ip' => $ip
     	));
     $nbr = $query->rowCount();

     if ($nbr == 0)
          return (false);
     else
          return (true);
}
