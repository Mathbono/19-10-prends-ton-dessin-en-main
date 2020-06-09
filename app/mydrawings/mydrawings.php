<?php

$root = '../../';

require_once $root . 'classes/config/FlashBag.php';
require_once $root . 'classes/user/connection/StateSession.php';
require_once $root . 'classes/user/File.php';
require_once $root . 'classes/user/Search.php';
require_once $root . 'classes/guest/Visited.php' ;

$app = '../';
$templates = 'mydrawings/mydrawings';
$title = 'Tous mes dessins';

$flash = new FlashBag();
$userSession = new StateSession();
$file = new File();
$search = new Search();
$visited = new Visited();
$guest = $visited->getData();

$dirPicture = $root . 'img/users/drawings/' . $userSession->getId() . '/';
$dirComment = $root . 'doc/users/drawings/' . $userSession->getId() . '/';
$contentDirPicture = array_diff(scandir($dirPicture), array('..', '.', 'private'));
sort($contentDirPicture, SORT_NATURAL | SORT_FLAG_CASE);
$nbPictures = count($contentDirPicture);
$limit = 5;
$page = isset($_GET['page']) ? htmlspecialchars($_GET['page']) : 1;
$nbPages = ceil($nbPictures / $limit);

if(!isset($_SERVER['HTTP_REFERER'])) {
    if(!$userSession->isLogged()) {
        header('Location:' . $root);
    }
}

require_once $root . 'layout.phtml';