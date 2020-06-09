<?php

$root = '../../';

require_once $root . 'classes/config/Database.php';
require_once $root . 'classes/user/connection/StateSession.php';
require_once $root . 'classes/config/FlashBag.php';
require_once $root . 'classes/user/Messaging.php';
require_once $root . 'classes/guest/Visited.php';

$app = '../';
$templates = 'messaging/received';
$title = 'Messagerie';

$limit = 15;
$page = isset($_GET['page']) ? htmlspecialchars($_GET['page']) : 1;
$beginning = ($page - 1) * $limit;

$db = new Database();
$flash = new FlashBag();
$userSession = new StateSession();
$messaging = new Messaging();
$receivedCouriers = $messaging->displayReceivedCouriers($beginning, $limit);
$nbReceivedCouriers = $messaging->allReceivedCouriers();
$nbPages = ceil($nbReceivedCouriers / $limit);
$visited = new Visited();
$guest = $visited->getData();

if(!$userSession->isLogged()) {
    header('Location:' . $root);
}

require_once $root . 'layout.phtml';