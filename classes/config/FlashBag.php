<?php

class FlashBag {

    public function __construct() {

        // Avons-nous une session ?
        if(session_status() == PHP_SESSION_NONE) {

            // Si non, créons-là
            session_start();
        }

        // Avons-nous un flashbag ?
        if(array_key_exists('flashbag', $_SESSION) === false) {

            // Si non, créons-le
            $_SESSION['flashbag'] = array();
        }
    }

    public function add($message) {

        // Ajoute un message au flashbag
        array_push($_SESSION['flashbag'], $message);
    }

    public function lastMessage() {

        // Retourne et supprime le message le plus récent
        return array_pop($_SESSION['flashbag']);
    }

    public function firstMessage() {

        // Retourne et supprime le message le plus ancien
        return array_shift($_SESSION['flashbag']);
    }

    public function fetchMessages() {

        // Retourne et supprime tous les messages
        $messages = $_SESSION['flashbag'];

        // Le flashbag est maintenant vide
        $_SESSION['flashbag'] = array();

        return $messages;
    }

    public function hasMessages()
    {
        // Avons-nous des messages dans le flashbag ?
        return empty($_SESSION['flashbag']) === false;
    }
}