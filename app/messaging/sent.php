<?php

$root = '../../';

require_once $root . 'classes/config/Database.php';
require_once $root . 'classes/user/connection/StateSession.php';
require_once $root . 'classes/config/FlashBag.php';
require_once $root . 'classes/user/Messaging.php';
require_once $root . 'classes/guest/Visited.php';

$app = '../';
$templates = 'messaging/sent';
$title = 'Messages envoyés';

$limit = 15;
$page = isset($_GET['page']) ? htmlspecialchars($_GET['page']) : 1;
$beginning = ($page - 1) * $limit;

$db = new Database();
$flash = new FlashBag();
$userSession = new StateSession();
$messaging = new Messaging();
$sentCouriers = $messaging->displaySentCouriers($beginning, $limit);
$nbSentCouriers = $messaging->allSentCouriers();
$nbPages = ceil($nbSentCouriers / $limit);
$visited = new Visited();
$guest = $visited->getData();

if(!$userSession->isLogged()) {
    header('Location:' . $root);
}

require_once $root . 'layout.phtml';