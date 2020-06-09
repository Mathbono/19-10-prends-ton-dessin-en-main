<?php

$root = '../../';

require_once $root . 'classes/config/Database.php';
require_once $root . 'classes/config/FlashBag.php';
require_once $root . 'classes/user/connection/StateSession.php';
require_once $root . 'classes/user/Forum.php';
require_once $root . 'classes/user/Search.php';
require_once $root . 'classes/guest/Visited.php' ;

$app = '../';
$templates = 'forum/mymessages';
$title = 'Tous mes messages';

$limit = 15;
$page = isset($_GET['page']) ? htmlspecialchars($_GET['page']) : 1;
$beginning = ($page - 1) * $limit;

$db = new Database();
$flash = new FlashBag();
$userSession = new StateSession();
$forum = new Forum();
$myMessages = $forum->displayMyMessages($beginning, $limit);
$nbMyMessages = $forum->allMyMessages();
$nbPages = ceil($nbMyMessages / $limit);
$search = new Search();
$visited = new Visited();
$guest = $visited->getData();

if(!isset($_SERVER['HTTP_REFERER'])) {
    if(!$userSession->isLogged()) {
        header('Location:' . $root);
    }
}

require_once $root . 'layout.phtml';