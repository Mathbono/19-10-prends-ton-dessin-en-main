<?php

$root = '../';

require_once $root . 'classes/user/connection/StateSession.php';
require_once $root . 'classes/config/FlashBag.php';
require_once $root . 'classes/guest/Visited.php';

$app = '';
$templates = 'aboutme';
$title = 'À propos de moi';

$flash = new FlashBag();
$userSession = new StateSession();
$visited = new Visited();
$guest = $visited->getData();

require_once $root . 'layout.phtml';