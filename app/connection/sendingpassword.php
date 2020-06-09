<?php

$root = '../../';

require_once $root . 'classes/config/FlashBag.php';
require_once $root . 'classes/user/connection/StateSession.php';
require_once $root . 'classes/user/Mailing.php';
require_once $root . 'classes/guest/Visited.php' ;

$app = '../';
$templates = 'connection/sendingpassword';
$title = 'Envoi de mot de passe';

$flash = new FlashBag();
$userSession = new StateSession();
$mail  = new Mailing();
$visited = new Visited();
$guest = $visited->getData();

if(!isset($_SERVER['HTTP_REFERER'])) {
    if($userSession->isLogged()) {
        $userSession->logout();
    }
}

require_once $root . 'layout.phtml';