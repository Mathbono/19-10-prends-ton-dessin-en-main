<?php

$root = '../../';

require_once $root . 'classes/user/connection/StateSession.php';
require_once $root . 'classes/config/FlashBag.php';
require_once $root . 'classes/user/File.php';
require_once $root . 'classes/guest/Visited.php';

$app = '../';
$templates = 'searchdrawings/finduser';
$title = 'Voir les dessins';

$flash = new FlashBag();
$userSession = new StateSession();
$file = new File();
$visited = new Visited();
$guest = $visited->getData();

require_once $root . 'layout.phtml';