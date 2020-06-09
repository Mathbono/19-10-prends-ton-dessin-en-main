<?php

$root = '../../';

require_once $root . 'classes/user/connection/StateSession.php';
require_once $root . 'classes/config/FlashBag.php';
require_once $root . 'classes/user/Messaging.php';
require_once $root . 'classes/guest/Visited.php';

$app = '../';
$templates = 'messaging/write';
$title = 'Écrire un message';

$flash = new FlashBag();
$userSession = new StateSession();
$messaging = new Messaging();
$visited = new Visited();
$guest = $visited->getData();

if(!$userSession->isLogged()) {
    header('Location:' . $root);
}

require_once $root . 'layout.phtml';