<?php

$root = '../../';

require_once $root . 'classes/config/Database.php';
require_once $root . 'classes/config/FlashBag.php';
require_once $root . 'classes/user/connection/StateSession.php';
require_once $root . 'classes/user/Forum.php';
require_once $root . 'classes/user/Search.php';
require_once $root . 'classes/guest/Visited.php';

$app = '../';
$templates = 'forum/subjects';
$title = 'Liste des sujets';

$idTheme = isset($_GET['idTheme']) ? htmlspecialchars($_GET['idTheme']) : null;
$limit = 15;
$page = isset($_GET['page']) ? htmlspecialchars($_GET['page']) : 1;
$beginning = ($page - 1) * $limit;

$db = new Database();
$flash = new FlashBag();
$userSession = new StateSession();
$forum = new Forum();
$subjects = $forum->displaySubjects($idTheme, $beginning, $limit);
$nbSubjects = $forum->allSubjects($idTheme);
$nbPages = ceil($nbSubjects / $limit);
$search = new Search();
$visited = new Visited();
$guest = $visited->getData();

if(!isset($_SERVER['HTTP_REFERER'])) {
    header('Location:' . $root);
}

require_once $root . 'layout.phtml';