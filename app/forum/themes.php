<?php

$root = '../../';

require_once $root . 'classes/config/Database.php';
require_once $root . 'classes/config/FlashBag.php';
require_once $root . 'classes/user/connection/StateSession.php';
require_once $root . 'classes/user/Forum.php';
require_once $root . 'classes/user/Search.php';
require_once $root . 'classes/guest/Visited.php';

$app = '../';
$templates = 'forum/themes';
$title = 'Forum';

$db = new Database();
$flash = new FlashBag();
$userSession = new StateSession();
$forum = new Forum();
$themes = $forum->displayThemes();
$search = new Search();
$visited = new Visited();
$guest = $visited->getData();
$i = 0;

foreach($themes as $theme) {

    $nb = $db->queryOne('SELECT COUNT(*) AS nb FROM subjects WHERE id_theme = ?', [$theme['id_theme']]);
    $nbSubjects[] = $nb['nb'];

    $idLastSubject = $db->queryOne('SELECT MAX(id_subject) AS id_last FROM subjects WHERE id_theme = ?', [$theme['id_theme']]);
    $datetime = $db->queryOne('SELECT DATE_FORMAT(created_at, \'%d/%m/%y %Hh%i\') AS created_at FROM subjects WHERE id_subject = ? AND id_theme = ?', [$idLastSubject['id_last'], $theme['id_theme']]);
    $datetimeLastSubject[] = $datetime['created_at'];
}

require_once $root . 'layout.phtml';