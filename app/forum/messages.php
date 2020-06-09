<?php

$root = '../../';

require_once $root . 'classes/config/Database.php';
require_once $root . 'classes/config/FlashBag.php';
require_once $root . 'classes/user/connection/StateSession.php';
require_once $root . 'classes/user/Forum.php';
require_once $root . 'classes/user/Search.php';
require_once $root . 'classes/guest/Visited.php';

$app = '../';
$templates = 'forum/messages';
$title = 'Liste des messages';

$idSubject = isset($_GET['idSubject']) ? htmlspecialchars($_GET['idSubject']) : null;
$limit = 15;
$page = isset($_GET['page']) ? htmlspecialchars($_GET['page']) : 1;
$beginning = ($page - 1) * $limit;

$db = new Database();
$flash = new FlashBag();
$userSession = new StateSession();
$forum = new Forum();
$messages = $forum->displayMessages($idSubject, $beginning, $limit);
$nbMessages = $forum->allMessages($idSubject);
$nbPages = ceil($nbMessages / $limit);
$search = new Search();
$visited = new Visited();
$guest = $visited->getData();

if(!isset($_SERVER['HTTP_REFERER'])) {
    header('Location:' . $root);
}

if((isset($_POST['submit']) && ($_POST['submit'] === 'Je m\'authentifie'))) {

    $connection = isset($_POST['connection']) ? htmlspecialchars($_POST['connection']) : null;

    if($connection === 'login') {

        header('Location:' . $root . 'app/connection/login.php');
    }
    else if($connection === 'register') {

        header('Location:' . $root . 'app/connection/register.php');
    }
}

require_once $root . 'layout.phtml';