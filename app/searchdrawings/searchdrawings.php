<?php

$root = '../../';

require_once $root . 'classes/user/connection/StateSession.php';
require_once $root . 'classes/config/FlashBag.php';
require_once $root . 'classes/guest/Visited.php';

$app = '../';
$templates = 'searchdrawings/searchdrawings';
$title = 'Dessins des utilisateurs';

$idUser = isset($_GET['id']) ? htmlspecialchars($_GET['id']) : null;

$flash = new FlashBag();
$userSession = new StateSession();
$visited = new Visited();
$guest = $visited->getData();

if(!isset($_SERVER['HTTP_REFERER'])) {
    header('Location:' . $root);
}

$dirPicture = $root . 'img/users/drawings/' . $idUser . '/';
$dirComment = $root . 'doc/users/drawings/' . $idUser . '/';
$contentDirPicture = array_diff(array_diff(scandir($dirPicture), array('..', '.', 'private')), array_diff(scandir($dirPicture . 'private/'), array('..', '.')));
sort($contentDirPicture, SORT_NATURAL | SORT_FLAG_CASE);
$nbPictures = count($contentDirPicture);
$limit = 5;
$page = isset($_GET['page']) ? htmlspecialchars($_GET['page']) : 1;
$nbPages = ceil($nbPictures / $limit);

require_once $root . 'layout.phtml';