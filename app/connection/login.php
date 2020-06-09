<?php

$root = '../../';

require_once $root . 'classes/config/FlashBag.php';
require_once $root . 'classes/user/connection/StateSession.php';
require_once $root . 'classes/user/connection/Login.php';
require_once $root . 'classes/guest/Visited.php' ;

$app = '../';
$templates = 'connection/login';
$title = 'Me connecter';

$flash = new FlashBag();
$userSession = new StateSession();
$log = new Login();
$visited = new Visited();
$guest = $visited->getData();

if(!isset($_SERVER['HTTP_REFERER'])) {
    if($userSession->isLogged()) {
        $userSession->logout();
    }
}

$_SERVER['PROTOCOL'] = isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) ? 'https' : 'http';
$referer = strstr(@$_SERVER['HTTP_REFERER'], '?', true);

if($referer === $_SERVER['PROTOCOL'] . '://' . $_SERVER['HTTP_HOST'] . '/prends-ton-dessin-en-main/app/forum/messages.php') {

    $_SESSION['query'] = strstr($_SERVER['HTTP_REFERER'], '?', false);
}

require_once $root . 'layout.phtml';