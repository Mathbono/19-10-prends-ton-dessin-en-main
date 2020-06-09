<?php

$root = '../../';

require_once $root . 'classes/user/connection/StateSession.php';
require_once $root . 'classes/user/connection/Logout.php';

$userSession = new StateSession();
$out = new Logout();

if(!$userSession->isLogged()) {
    header('Location:' . $root);
}