<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/prends-ton-dessin-en-main/classes/user/connection/DbReq.php';

class Login {
    private $root;
    private $flash;
    private $user;
    private $userSession;

    public function __construct() {
        $this->root = '../../';
        $this->flash = new FlashBag();
        $this->user = new DbReq();
        $this->userSession = new StateSession();

        if ((isset($_POST['submit']) && ($_POST['submit'] === 'Je me connecte'))) {

            $login = isset($_POST['login']) ? htmlspecialchars($_POST['login']) : null;
            $password = isset($_POST['password']) ? htmlspecialchars($_POST['password']) : null;

            $userInfo = $this->user->checkLogin($login, $password);

            // Si les identifiants sont corrects
            if($userInfo !== false) {

                // Connexion
                $this->userSession->login($userInfo);

                $this->flash->add('Vous+êtes+désormais+connecté+!');

                if(empty($_SESSION['query'])) {

                    // Rediriger vers la home
                    header('Location:' . $this->root . 'index.php?flash=' . $this->flash->lastMessage());
                }
                else {

                    // Si je souhaite me connecter depuis le forum, rediriger où j'étais
                    header('Location:' . $this->root . 'app/forum/messages.php' . $_SESSION['query'] . '&flash=' . $this->flash->lastMessage());
                    unset($_SESSION['query']);
                }
            }
        }
    }
}