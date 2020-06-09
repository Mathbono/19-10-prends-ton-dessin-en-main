<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/prends-ton-dessin-en-main/classes/user/connection/StateSession.php';

class Mailing {
    private $db;
    private $flash;
    private $userSession;

    public function __construct() {
        $this->db = new Database();
        $this->flash = new FlashBag();
        $this->userSession = new StateSession();

        if((isset($_POST['submit']) && ($_POST['submit'] === 'Envoyer le message'))) {

            $captcha = isset($_POST['captcha']) ? htmlspecialchars($_POST['captcha']) : null;
            $object = isset($_POST['object']) ? htmlspecialchars($_POST['object']) : null;

            if($object !== 'Objet du message') {

                if($captcha === $_SESSION['captcha']) {

                    $this->contact();
                }
                else {
                    $this->flash->add('Vous n\'avez pas fourni le mot indiqué<br>'
                        . 'Nous devons vérifier que vous n\'êtes pas une machine'
                    );
                }
            }
            else {
                $this->flash->add('Vous devez renseigner l\'objet du message<br>'
                    . 'Vous pouvez renseigner "Autre" si vous ne trouvez pas l\'objet de votre message'
                );
            }
        }

        if((isset($_POST['submit']) && ($_POST['submit'] === 'Recevoir mon nouveau mot de passe'))) {

            $this->sendingPassword();
        }
    }

    public function contact() {

        $receiver = 'bonomini.mathieu@yahoo.fr';
        $sender = 'From: ' . $this->userSession->getLogin() . '<' . $this->userSession->getEmail() . '>';
        $object = isset($_POST['object']) ? htmlspecialchars($_POST['object']) : null;
        $text = isset($_POST['message']) ? htmlspecialchars($_POST['message']) : null;

        // En cas d'absence de JavaScript
        if(strlen($text) >= 5) {

            if(strlen($text) <= 10000) {

                $message = str_replace('\n.', '\n..', $text);

                if (@mail($receiver, $object, $message, $sender)) {

                    $this->flash->add('Votre+message+a+bien+été+envoyé.+Merci+de+votre+retour+!');

                    // Rediriger vers la home
                    header('Location:../index.php?flash=' . $this->flash->lastMessage());

                }
                else {
                    $this->flash->add('La connexion ou requête a échoué');
                }
            }
            else {
                $this->flash->add('Votre message ne peut excéder 10.000 caractères');
            }
        }
        else {
            $this->flash->add('Votre message doit contenir au moins 5 caractères');
        }
    }

    public function sendingPassword() {

        $loginMail = isset($_POST['loginMail']) ? htmlspecialchars($_POST['loginMail']) : null;

        try {
            $user = $this->db->queryOne(
                'SELECT *
                FROM users
                WHERE login = ? OR email = ?',
                [$loginMail, $loginMail]
            );
        }
        catch (PDOException $e) {

            $this->flash->add('La connexion ou requête a échoué<br>Informations : ' . $e->getMessage());
        }

        // Si on trouve l'utilisateur, continuer
        if (($user !== false) && ($loginMail !== '')) {

            // Déterminer, envoyer nouveau MDP, et mettre à jour ancien MDP
            $password = $this->generatePassword(8);

            $receiver = $user['email'];
            $sender = 'From: ' . $user['login'];
            $object = 'Nouveau mot de passe';
            $text = 'Vous avez demandé à ce que votre mot de passe soit réinitialisé.'
                . 'Votre nouveau mot de passe est : ' . $password;
            $message = str_replace('\n.', '\n..', $text);

            if (@mail($receiver, $object, $message, $sender)) {

                $this->db->sql(
                    'UPDATE users
            SET password = ?
            WHERE id_user = ?',
                    [password_hash($password, PASSWORD_DEFAULT), $user['id_user']]
                );

                unset($password);

                $this->flash->add('Votre+nouveau+mot+de+passe+vous+a+bien+été+envoyé+!');

                // Rediriger vers la home
                header('Location:../../index.php?flash=' . $this->flash->lastMessage());

            } else {
                $this->flash->add('La connexion ou requête a échoué');
            }
        }
        else {
            $this->flash->add('Le pseudo ou e-mail n\'est pas connu du site');
        }
    }

    public function register($login, $email) {

        $receiver = $email;
        $sender = 'From: ' . $login;
        $object = 'Inscription "Prends ton dessin en main !"';
        $text = 'Vous êtes désormais enregistré sur notre site ! Votre pseudo est : ' . $login;
        $message = str_replace('\n.', '\n..', $text);

        @mail($receiver, $object, $message, $sender);
    }

    public function generatePassword(int $size) {

        // Initialisation des caractères utilisables
        $characters = array('-', '+', '*', '/', '=', '!', '?', '(', ')', '@', 'à', 'é', 'è', 'ç', 1, 2, 3, 4, 5, 6, 7, 8, 9, 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'm', 'n', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');

        for($i = 0; $i < $size; $i++) {

            $password = isset($password) ? $password : null;
            $password .= ($i%2) ? mb_strtolower($characters[array_rand($characters)], 'UTF-8') : $characters[array_rand($characters)];
        }

        return $password;
    }
}