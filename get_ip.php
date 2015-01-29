<?php
//Retourne la vraie adresse IP
function get_ip() {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else
                $ip = $_SERVER['REMOTE_ADDR'];
        return $ip;
}
?>