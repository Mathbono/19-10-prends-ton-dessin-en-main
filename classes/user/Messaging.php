<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/prends-ton-dessin-en-main/classes/config/Database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/prends-ton-dessin-en-main/classes/config/FlashBag.php';

class Messaging {
    private $db;
    private $flash;
    private $userSession;

    public function __construct() {
        $this->db = new Database();
        $this->flash = new FlashBag();
        $this->userSession = new StateSession();

        if((isset($_POST['submit']) && ($_POST['submit'] === 'Envoyer le message'))) {

            $this->writeCourier();
        }
    }

    public function allReceivedCouriers() {

        try {
            $nbReceivedCouriers = $this->db->queryOne(
                'SELECT COUNT(*) as nb
                FROM couriers
                WHERE id_receiver = ?',
                [$this->userSession->getId()]
            );
            $nbReceivedCouriers = $nbReceivedCouriers['nb'];

            return $nbReceivedCouriers;
        }
        catch (PDOException $e) {

            $this->flash->add('La connexion ou requête a échoué<br>Informations : ' . $e->getMessage());
        }
    }

    public function allSentCouriers() {

        try {
            $nbSentCouriers = $this->db->queryOne(
                'SELECT COUNT(*) as nb
                FROM couriers
                WHERE id_writer = ?',
                [$this->userSession->getId()]
            );
            $nbSentCouriers = $nbSentCouriers['nb'];

            return $nbSentCouriers;
        }
        catch (PDOException $e) {

            $this->flash->add('La connexion ou requête a échoué<br>Informations : ' . $e->getMessage());
        }
    }

    public function displayReceivedCouriers($beginning, $limit) {

        try {
            $receivedCouriers = $this->db->getPdo()->prepare(
                'SELECT id_courier, title, content, DATE_FORMAT(created_at, \'%d/%m/%y %Hh%i\') AS created_at, id_writer, id_receiver
                FROM couriers
                WHERE id_receiver = :idReceiver
                LIMIT :max
                OFFSET :min'
            );

            $receivedCouriers->bindValue(':idReceiver', $this->userSession->getId(), PDO::PARAM_INT);
            $receivedCouriers->bindValue(':min', $beginning, PDO::PARAM_INT);
            $receivedCouriers->bindValue(':max', $limit, PDO::PARAM_INT);
            $receivedCouriers->execute();

            return $receivedCouriers;
        }
        catch (PDOException $e) {

            $this->flash->add('La connexion ou requête a échoué<br>Informations : ' . $e->getMessage());
        }
    }

    public function displaySentCouriers($beginning, $limit) {

        try {
            $sentCouriers = $this->db->getPdo()->prepare(
                'SELECT id_courier, title, content, DATE_FORMAT(created_at, \'%d/%m/%y %Hh%i\') AS created_at, id_writer, id_receiver
                FROM couriers
                WHERE id_writer = :idWriter
                LIMIT :max
                OFFSET :min'
            );

            $sentCouriers->bindValue(':idWriter', $this->userSession->getId(), PDO::PARAM_INT);
            $sentCouriers->bindValue(':min', $beginning, PDO::PARAM_INT);
            $sentCouriers->bindValue(':max', $limit, PDO::PARAM_INT);
            $sentCouriers->execute();

            return $sentCouriers;
        }
        catch (PDOException $e) {

            $this->flash->add('La connexion ou requête a échoué<br>Informations : ' . $e->getMessage());
        }
    }

    public function writeCourier() {

        $receiver = isset($_POST['receiver']) ? htmlspecialchars($_POST['receiver']) : null;
        $title = isset($_POST['title']) ? htmlspecialchars($_POST['title']) : null;
        $courier = isset($_POST['courier']) ? htmlspecialchars($_POST['courier']) : null;

        // En cas d'absence de JavaScript
        if(strlen($title) >= 15) {

            if(strlen($title) <= 255) {

                if(strlen($courier) >= 15) {

                    if(strlen($courier) <= 10000) {

                        try {
                            // Vérifier si le login est présent dans la base
                            $res = $this->db->queryOne(
                                'SELECT *
                                FROM users
                                WHERE login = ?',
                                [$receiver]
                            );
                        }
                        catch (PDOException $e) {

                            $this->flash->add('La connexion ou requête a échoué<br>Informations : ' . $e->getMessage());
                        }

                        // Si le login est présent en base, continuer
                        if ($res !== false) {

                            try {
                                // Lancer la requête d'insertion dans la BDD
                                $this->db->sql(
                                    'INSERT INTO couriers
                                ( 
                                    title,
                                    content,
                                    created_at,
                                    id_writer,
                                    id_receiver
                                )
                                VALUES 
                                ( 
                                    :title,
                                    :content,
                                    NOW(),
                                    :id_writer,
                                    :id_receiver
                                )',
                                    [
                                        ':title'       => $title,
                                        ':content'     => $courier,
                                        ':id_writer'   => $this->userSession->getId(),
                                        ':id_receiver' => $res['id_user']
                                    ]
                                );
                            }
                            catch (PDOException $e) {

                                $this->flash->add('La connexion ou requête a échoué<br>Informations : ' . $e->getMessage());
                            }

                            $this->flash->add('Votre message a été envoyé !');
                        }
                        else {
                            $this->flash->add('Ce pseudo n\'existe pas');
                        }
                    }
                    else {
                        $this->flash->add('Votre message ne peut excéder 10.000 caractères');
                    }
                }
                else {
                    $this->flash->add('Votre message doit contenir au moins 15 caractères');
                }
            }
            else {
                $this->flash->add('Votre titre ne peut excéder 255 caractères');
            }
        }
        else {
            $this->flash->add('Votre titre doit contenir au moins 15 caractères');
        }
    }
}