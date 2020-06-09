<?php

$root = '../../';

require_once $root . 'classes/config/FlashBag.php';
require_once $root . 'classes/user/connection/StateSession.php';
require_once $root . 'classes/user/connection/Register.php';
require_once $root . 'classes/guest/Visited.php' ;

$app = '../';
$templates = 'connection/register';
$title = 'M\'enregistrer';

$flash = new FlashBag();
$userSession = new StateSession();
$reg = new Register();
$visited = new Visited();
$guest = $visited->getData();

if(!isset($_SERVER['HTTP_REFERER'])) {
    if ($userSession->isLogged()) {
        $userSession->logout();
    }
}

$_SERVER['PROTOCOL'] = isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) ? 'https' : 'http';
$referer = strstr(@$_SERVER['HTTP_REFERER'], '?', true);

if($referer === $_SERVER['PROTOCOL'] . '://' . $_SERVER['HTTP_HOST'] . '/prends-ton-dessin-en-main/app/forum/messages.php') {

    $_SESSION['query'] = strstr($_SERVER['HTTP_REFERER'], '?', false);
}

require_once $root . 'layout.phtml';