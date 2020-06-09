<?php

$root = '../';

require_once $root . 'classes/config/FlashBag.php';
require_once $root . 'classes/user/connection/StateSession.php';
require_once $root . 'classes/user/Mailing.php';
require_once $root . 'classes/guest/Visited.php';
require_once $root . 'classes/user/Captcha.php';

$app = '';
$templates = 'contact';
$title = 'Contact';

$flash = new FlashBag();
$userSession = new StateSession();
$mail  = new Mailing();
$captcha = new Captcha();
$text = $captcha->displayCaptcha();
$visited = new Visited();
$guest = $visited->getData();

if(!isset($_SERVER['HTTP_REFERER'])) {
    if(!$userSession->isLogged()) {
        header('Location:' . $root);
    }
}

require_once $root . 'layout.phtml';