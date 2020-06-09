<?php

$root = '../';

require_once $root . 'classes/config/FlashBag.php';
require_once $root . 'classes/user/connection/StateSession.php';
require_once $root . 'classes/user/File.php';
require_once $root . 'classes/guest/Visited.php' ;

$app = '';
$templates = 'draw';
$title = 'Dessiner';

$picture = isset($_POST['picture']) ? htmlspecialchars($_POST['picture']) : null;
$name = isset($_POST['title']) ? htmlspecialchars($_POST['title']) : null;

$flash = new FlashBag();
$userSession = new StateSession();
$file = new File();
$file->save($picture, $name);
$visited = new Visited();
$guest = $visited->getData();

$dirPicture = $root . 'img/users/drawings/' . $userSession->getId() . '/';
$dirComment = $root . 'doc/users/drawings/' . $userSession->getId() . '/';

require_once $root . 'layout.phtml';