<?php

require_once '../classes/config/FlashBag.php';
require_once '../classes/user/connection/StateSession.php';

$flash = new FlashBag();
$userSession = new StateSession();

$_SERVER['PROTOCOL'] = isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) ? 'https' : 'http';
$root = $_SERVER['PROTOCOL'] . '://' . $_SERVER['HTTP_HOST'] . '/prends-ton-dessin-en-main/';
$app = $_SERVER['PROTOCOL'] . '://' . $_SERVER['HTTP_HOST'] . '/prends-ton-dessin-en-main/app/';
$templates = 'error';
$title = 'Erreur !';

switch(@$_GET['err']) {
    case '400':
        $error = 'Échec de l\'analyse HTTP';
        break;
    case '401':
        $error = 'Le pseudo ou le mot de passe est incorrect';
        break;
    case '402':
        $error = 'Le client doit reformuler sa demande avec les données correctes';
        break;
    case '403':
        $error = 'Cette requête est interdite !';
        break;
    case '404':
        $error = 'La page n\'existe pas ou plus !';
        break;
    case '405':
        $error = 'Cette méthode n\'est pas autorisée';
        break;
    case '500':
        $error = 'Erreur interne au serveur ou serveur saturé';
        break;
    case '501':
        $error = 'Le serveur ne supporte pas le service demandé';
        break;
    case '502':
        $error = 'Mauvaise passerelle';
        break;
    case '503':
        $error = 'Le service est indisponible';
        break;
    case '504':
        $error = 'Le temps de réponse est trop important';
        break;
    case '505':
        $error = 'La version HTTP n\'est pas supportée';
        break;
    default:
        $error = '{{(>_<)}} Erreur !';
}

require_once '../layout.phtml';