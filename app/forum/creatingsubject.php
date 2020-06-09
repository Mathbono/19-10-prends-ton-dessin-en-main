<?php

$root = '../../';

require_once $root . 'classes/config/FlashBag.php';
require_once $root . 'classes/user/connection/StateSession.php';
require_once $root . 'classes/user/Forum.php';
require_once $root . 'classes/guest/Visited.php' ;

$app = '../';
$templates = 'forum/creatingsubject';
$title = 'Création d\'un sujet';

$limit = 15;

$flash = new FlashBag();
$userSession = new StateSession();
$forum = new Forum();
$themes = $forum->displayThemes();
$visited = new Visited();
$guest = $visited->getData();

if(!isset($_SERVER['HTTP_REFERER'])) {
    if(!$userSession->isLogged()) {
        header('Location:' . $root);
    }
}

require_once $root . 'layout.phtml';