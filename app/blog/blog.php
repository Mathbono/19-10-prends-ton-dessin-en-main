<?php

$root = '../../';

require_once $root . 'classes/user/connection/StateSession.php';
require_once $root . 'classes/config/FlashBag.php';
require_once $root . 'classes/user/Search.php';
require_once $root . 'classes/guest/Visited.php';

$app = '../';
$templates = 'blog/blog';
$title = 'Blog';

$flash = new FlashBag();
$userSession = new StateSession();
$search = new Search();
$visited = new Visited();
$guest = $visited->getData();

$dirPicture = $root . 'img/drawings/';
$dirComment = $root . 'doc/drawings/';
$contentDirPicture = array_diff(scandir($dirPicture), array('..', '.'));
sort($contentDirPicture, SORT_NATURAL | SORT_FLAG_CASE);
$nbPictures = count($contentDirPicture);
$limit = 5;
$page = isset($_GET['page']) ? htmlspecialchars($_GET['page']) : 1;
$nbPages = ceil($nbPictures / $limit);

require_once $root . 'layout.phtml';