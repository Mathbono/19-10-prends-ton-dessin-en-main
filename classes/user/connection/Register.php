<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/prends-ton-dessin-en-main/classes/user/connection/DbReq.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/prends-ton-dessin-en-main/classes/user/Mailing.php';

class Register {
    private $root;
    private $flash;
    private $user;
    private $userSession;
    private $mailing;

    public function __construct() {
        $this->root = '../../';
        $this->flash = new FlashBag();
        $this->user = new DbReq();
        $this->userSession = new StateSession();
        $this->mailing = new Mailing();

        if((isset($_POST['submit']) && ($_POST['submit'] === 'Je m\'enregistre'))) {

            $login = isset($_POST['login']) ? htmlspecialchars($_POST['login']) : null;
            $password = isset($_POST['password']) ? htmlspecialchars($_POST['password']) : null;
            $verifpassword = isset($_POST['verifpassword']) ? htmlspecialchars($_POST['verifpassword']) : null;
            $name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : null;
            $firstname = isset($_POST['firstname']) ? htmlspecialchars($_POST['firstname']) : null;
            $email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : null;

            // En cas d'absence de JavaScript
            if(strlen($login) >= 8) {

                if(strlen($login) <= 50) {

                    if(strlen($password) <= 50) {

                        if(preg_match('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/', $password) === 1) {

                            if($verifpassword === $password) {

                                if(strlen($name) >= 2) {

                                    if(strlen($name) <= 50) {

                                        if(strlen($firstname) >= 2) {

                                            if(strlen($firstname) <= 50) {

                                                if(preg_match('/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/', $email) === 1) {

                                                    // Enregistrement
                                                    $error = $this->user->register($login, $password, $name, $firstname, $email);

                                                    // Si pas d'erreur en interne
                                                    if($error !== true) {

                                                        // Envoi de mail
                                                        $this->mailing->register($login, $email);

                                                        $userInfo = $this->user->checkLogin($login, $password);

                                                        // Si les identifiants sont corrects
                                                        if($userInfo !== false) {

                                                            // Connexion
                                                            $this->userSession->login($userInfo);

                                                            // Création des dossiers de l'espace perso
                                                            mkdir($this->root . 'img/users/drawings/' . $this->userSession->getId() . '/');
                                                            mkdir($this->root . 'img/users/drawings/' . $this->userSession->getId() . '/private/');
                                                            mkdir($this->root . 'doc/users/drawings/' . $this->userSession->getId() . '/');

                                                            $this->flash->add(
                                                                'Votre+compte+a+bien+été+enregistré,+vous+pouvez+désormais+utiliser+toutes+les+fonctionnalités+du+site+!'
                                                            );

                                                            if(empty($_SESSION['query'])) {

                                                                // Rediriger vers la home
                                                                header('Location:' . $this->root . 'index.php?flash=' . $this->flash->lastMessage());
                                                            }
                                                            else {

                                                                // Si je souhaite m'enregistrer depuis le forum, rediriger où j'étais
                                                                header('Location:' . $this->root . 'app/forum/messages.php' . $_SESSION['query'] . '&flash=' . $this->flash->lastMessage());
                                                                unset($_SESSION['query']);
                                                            }
                                                        }
                                                    }
                                                }
                                                else {
                                                    $this->flash->add('Vous devez fournir une adresse e-mail valide');
                                                }
                                            }
                                            else {
                                                $this->flash->add('Le prénom ne peut excéder 50 caractères');
                                            }
                                        }
                                        else {
                                            $this->flash->add('Le prénom doit contenir au moins 2 caractères');
                                        }
                                    }
                                    else {
                                        $this->flash->add('Le nom ne peut excéder 50 caractères');
                                    }
                                }
                                else {
                                    $this->flash->add('Le nom doit contenir au moins 2 caractères');
                                }
                            }
                            else {
                                $this->flash->add('Les deux mots de passe doivent être identiques');
                            }
                        }
                        else {
                            $this->flash->add(
                                'Le mot de passe doit contenir au moins 8 caractères dont une minuscule, une majuscule, un chiffre et un caractère spécial'
                            );
                        }
                    }
                    else {
                        $this->flash->add('Le mot de passe ne peut excéder 50 caractères');
                    }
                }
                else {
                    $this->flash->add('Le pseudo ne peut excéder 50 caractères');
                }
            }
            else {
                $this->flash->add('Le pseudo doit contenir au moins 8 caractères');
            }
        }
    }
}