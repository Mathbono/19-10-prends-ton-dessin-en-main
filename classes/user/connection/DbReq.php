<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/prends-ton-dessin-en-main/classes/config/Database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/prends-ton-dessin-en-main/classes/config/FlashBag.php';

class DbReq {
    private $db;
    private $flash;

    public function __construct() {
        $this->db = new Database();
        $this->flash = new FlashBag();
    }

    // Insère un client en base dont on passe les infos en paramètre
	public function register($login, $password, $name, $firstname, $email) {

        try {
            // Vérifier si le login passé en paramètre est déjà présent dans la base
            $res = $this->db->queryOne(
                'SELECT *
            FROM users
            WHERE login = ?',
                [$login]
            );
        }
        catch (PDOException $e) {

            $this->flash->add('La connexion ou requête a échoué<br>Informations : ' . $e->getMessage());
        }

        // Si le login n'est pas déjà présent en base, continuer
        if($res === false) {

            try {
                // Vérifier si le mail passé en paramètre est déjà présent dans la base
                $res = $this->db->queryOne(
                    'SELECT *
            FROM users
            WHERE email = ?',
                    [$email]
                );
            }
            catch (PDOException $e) {

                $this->flash->add('La connexion ou requête a échoué<br>Informations : ' . $e->getMessage());
            }

            // Si le mail n'est pas déjà présent en base, continuer
            if ($res === false) {

                try {
                    // Lancer la requête d'insertion dans la BDD
                    $this->db->sql(
                        'INSERT INTO users
				( 
					login, 
					password, 
					name, 
					firstname,
					email,
					created_at,
				    logged_at
				)
				VALUES 
				( 
					:login, 
					:password, 
					:name, 
					:firstname, 
					:email,
					NOW(),
				    NOW()
				)',
                        [
                            ':login'     => $login,
                            ':password'  => password_hash($password, PASSWORD_DEFAULT),
                            ':name'      => $name,
                            ':firstname' => $firstname,
                            ':email'     => $email
                        ]
                    );
                }
                catch (PDOException $e) {

                    $this->flash->add('La connexion ou requête a échoué<br>Informations : ' . $e->getMessage());
                }
            }
            else {
                $this->flash->add('Cette adressse e-mail est déjà utilisée');

                return true;
            }
        }
        else {
            $this->flash->add('Ce pseudo est déjà utilisé');

            return true;
        }
	}

	/*
	Méthode qui vérifie les identifiants de connexion
	Entrées : 
		- l'email à tester
		- le MDP à tester
	Sortie : un tableau associatif contenant l'id, le login, le nom, le prénom, l'email de l'utilisateur
	(s'il a réussi à se connecter)
	*/
	public function checkLogin($login, $password) {

        try {
            // Vérifier si le login est déjà présent en base
            $res = $this->db->queryOne(
                'SELECT id_user, login, password, name, firstname, email
            FROM users
            WHERE login = ?',
                [$login]
            );
        }
        catch (PDOException $e) {

            $this->flash->add('La connexion ou requête a échoué<br>Informations : ' . $e->getMessage());
        }

        // Si on trouve le login, on continue
        if(($res !== false) && ($login !== '')) {

            // Sinon, extraire de la BDD le MDP encrypté correspondant
            $pwd_reference = $res['password'];

            // Le MDP passé en paramètre est-il cohérent avec le MDP encrypté ?
            // Si oui, continuer
            if (password_verify($password, $pwd_reference) !== false) {

                // Si oui, l'utilisateur va être connecté. Renvoyer l'id de l'utilisateur et son nom
                unset($res['password']);

                try {
                    // Mettre à jour dans la BDD la date de dernière connexion
                    $this->db->sql(
                        'UPDATE users
			    SET logged_at = NOW()
			    WHERE login = ?',
                        [$login]
                    );

                    return $res;

                } catch (PDOException $e) {

                    $this->flash->add('La connexion ou requête a échoué<br>Informations : ' . $e->getMessage());
                }
            }
            else {
                $this->flash->add('Le pseudo ou le mot de passe est incorrect');

                return false;
            }
        }
        else {
            $this->flash->add('Le pseudo ou le mot de passe est incorrect');

            return false;
        }
	}
}