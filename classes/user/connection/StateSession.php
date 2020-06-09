<?php

class StateSession {

	public function __construct() {

        // Avons-nous une session ?
        if(session_status() == PHP_SESSION_NONE) {

            // Si non, créons-là
            session_start();
        }
    }
  
    // Créer les variables de session d'un nouvel utilisateur connecté
  	// $userInfo est un tableau associatif contenant les clés id_user, login, name, firstname, email
    public function login($userInfo) {
	    $_SESSION['user'] = $userInfo;
    }

    // Supprimer les variables de session de l'utilisateur
  	public function logout() {
    	unset($_SESSION['user']);
    }
  
  	// Retourne (sous forme de booléen) s'il y a ou non un utilisateur connecté
  	public function isLogged() {
      	return array_key_exists('user', $_SESSION);
    }
  
  	public function getId() {
    	return isset($_SESSION['user']) ? $_SESSION['user']['id_user'] : null;
    }

    public function getLogin() {
        return isset($_SESSION['user']) ? $_SESSION['user']['login'] : null;
    }

    public function getName() {
    	return isset($_SESSION['user']) ? $_SESSION['user']['name'] : null;
    }
  
    public function getFirstname() {
    	return isset($_SESSION['user']) ? $_SESSION['user']['firstname'] : null;
    }
  
    public function getEmail() {
    	return isset($_SESSION['user']) ? $_SESSION['user']['email'] : null;
    }
}