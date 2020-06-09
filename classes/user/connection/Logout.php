<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/prends-ton-dessin-en-main/classes/config/FlashBag.php';

class Logout {
    private $root;
    private $flash;
    private $userSession;

    public function __construct() {
        $this->root = '../../';
        $this->flash = new FlashBag();
        $this->userSession = new StateSession();

        // Déconnexion
    	$this->userSession->logout();

    	$this->flash->add('Vous êtes désormais déconnecté !');
    }
}