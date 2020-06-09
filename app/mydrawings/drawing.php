<?php

$root = '../../';

require_once $root . 'classes/config/FlashBag.php';
require_once $root . 'classes/user/connection/StateSession.php';
require_once $root . 'classes/guest/Visited.php' ;

$app = '../';
$templates = 'mydrawings/drawing';
$title = 'Mes dessins';

$flash = new FlashBag();
$userSession = new StateSession();
$visited = new Visited();
$guest = $visited->getData();

$dirPicture = $root . 'img/users/drawings/' . $userSession->getId() . '/';
$dirComment = $root . 'doc/users/drawings/' . $userSession->getId() . '/';
$contentDirPicture = array_diff(scandir($dirPicture), array('..', '.', 'private'));
sort($contentDirPicture, SORT_NATURAL | SORT_FLAG_CASE);
$nbPictures = count($contentDirPicture);
$contentDirComment = array_diff(scandir($dirComment), array('..', '.'));
sort($contentDirComment, SORT_NATURAL | SORT_FLAG_CASE);

if(!isset($_SERVER['HTTP_REFERER'])) {
    if(!$userSession->isLogged()) {
        header('Location:' . $root);
    }
}

foreach($contentDirPicture as $picture) {
    $name = stristr($picture, '.', true);
    $pictures[] = $picture;
    $names[] = $name;
}
foreach($contentDirComment as $doc) {
    $comment = file_get_contents($dirComment . $doc);
    $docs[] = $doc;
    $comments[] = $comment;
}
@$files = urlencode(json_encode([$names, $pictures, $docs, $comments, $dirPicture, $dirComment, $nbPictures]));

require_once $root . 'layout.phtml';