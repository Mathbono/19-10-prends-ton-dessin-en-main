<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/prends-ton-dessin-en-main/classes/config/Database.php';

class Forum {

    private $db;
    private $userSession;
    private $flash;

    public function __construct() {

        $this->db = new Database();
        $this->userSession = new StateSession();
        $this->flash = new FlashBag();

        if((isset($_POST['submit']) && ($_POST['submit'] === 'Nouveau sujet'))) {
            $this->createSubject();
        }
        if((isset($_POST['submit']) && ($_POST['submit'] === 'Nouveau message'))) {
            $this->createMessage();
        }
    }

    public function allSubjects($idTheme) {

        try {
            $nbSubjects = $this->db->queryOne(
                'SELECT COUNT(*) AS nb
                FROM subjects
                WHERE id_theme = ?',
                [$idTheme]
            );
            $nbSubjects = $nbSubjects['nb'];

            return $nbSubjects;
        }
        catch (PDOException $e) {

            $this->flash->add('La connexion ou requête a échoué<br>Informations : ' . $e->getMessage());
        }
    }

    public function allMessages($idSubject) {

        try {
            $nbMessages = $this->db->queryOne(
                'SELECT COUNT(*) AS nb
                FROM messages
                WHERE id_subject = ?',
                [$idSubject]
            );
            $nbMessages = $nbMessages['nb'];

            return $nbMessages;
        }
        catch (PDOException $e) {

            $this->flash->add('La connexion ou requête a échoué<br>Informations : ' . $e->getMessage());
        }
    }

    public function allMySubjects() {

        try {
            $nbMySubjects = $this->db->queryOne(
                'SELECT COUNT(*) AS nb
                FROM subjects
                WHERE id_user = ?',
                [$this->userSession->getId()]
            );
            $nbMySubjects = $nbMySubjects['nb'];

            return $nbMySubjects;
        }
        catch (PDOException $e) {

            $this->flash->add('La connexion ou requête a échoué<br>Informations : ' . $e->getMessage());
        }
    }

    public function allMyMessages() {

        try {
            $nbMyMessages = $this->db->queryOne(
                'SELECT COUNT(*) AS nb
                FROM messages
                WHERE id_user = ?',
                [$this->userSession->getId()]
            );
            $nbMyMessages = $nbMyMessages['nb'];

            return $nbMyMessages;
        }
        catch (PDOException $e) {

            $this->flash->add('La connexion ou requête a échoué<br>Informations : ' . $e->getMessage());
        }
    }

    public function displayThemes() {

        try {
            $themes = $this->db->queryAll(
                'SELECT id_theme, title
                FROM themes'
            );

            return $themes;
        }
        catch (PDOException $e) {

            $this->flash->add('La connexion ou requête a échoué<br>Informations : ' . $e->getMessage());
        }
    }

    public function displaySubjects($idTheme, $beginning, $limit) {

        try {
            $subjects = $this->db->getPdo()->prepare(
                'SELECT id_subject, title, DATE_FORMAT(created_at, \'%d/%m/%y %Hh%i\') AS created_at, id_user, id_theme
                FROM subjects
                WHERE id_theme = :idTheme
                ORDER BY id_subject DESC
                LIMIT :max
                OFFSET :min'
            );

            $subjects->bindValue(':idTheme', $idTheme, PDO::PARAM_INT);
            $subjects->bindValue(':min', $beginning, PDO::PARAM_INT);
            $subjects->bindValue(':max', $limit, PDO::PARAM_INT);
            $subjects->execute();

            return $subjects;
        }
        catch (PDOException $e) {

            $this->flash->add('La connexion ou requête a échoué<br>Informations : ' . $e->getMessage());
        }
    }

    public function displayMessages($idSubject, $beginning, $limit) {

        try {
            $messages = $this->db->getPdo()->prepare(
                'SELECT id_message, content, DATE_FORMAT(created_at, \'%d/%m/%y %Hh%i\') AS created_at, id_user, id_subject
                FROM messages
                WHERE id_subject = :idSubject
                ORDER BY id_message
                LIMIT :max
                OFFSET :min'
            );

            $messages->bindValue(':idSubject', $idSubject, PDO::PARAM_INT);
            $messages->bindValue(':min', $beginning, PDO::PARAM_INT);
            $messages->bindValue(':max', $limit, PDO::PARAM_INT);
            $messages->execute();

            return $messages;
        }
        catch (PDOException $e) {

            $this->flash->add('La connexion ou requête a échoué<br>Informations : ' . $e->getMessage());
        }
    }

    public function displayMySubjects($beginning, $limit) {

        try {
            $mySubjects = $this->db->getPdo()->prepare(
                'SELECT id_subject, title, DATE_FORMAT(created_at, \'%d/%m/%y %Hh%i\') AS created_at, id_user, id_theme
                FROM subjects
                WHERE id_user = :idUser
                ORDER BY id_subject DESC
                LIMIT :max
                OFFSET :min'
            );

            $mySubjects->bindValue(':idUser', $this->userSession->getId(), PDO::PARAM_INT);
            $mySubjects->bindValue(':min', $beginning, PDO::PARAM_INT);
            $mySubjects->bindValue(':max', $limit, PDO::PARAM_INT);
            $mySubjects->execute();

            return $mySubjects;
        }
        catch (PDOException $e) {

            $this->flash->add('La connexion ou requête a échoué<br>Informations : ' . $e->getMessage());
        }
    }

    public function displayMyMessages($beginning, $limit) {

        try {
            $myMessages = $this->db->getPdo()->prepare(
                'SELECT id_message, content, DATE_FORMAT(created_at, \'%d/%m/%y %Hh%i\') AS created_at, id_user, id_subject
                FROM messages
                WHERE id_user = :idUser
                ORDER BY id_message DESC
                LIMIT :max
                OFFSET :min'
            );

            $myMessages->bindValue(':idUser', $this->userSession->getId(), PDO::PARAM_INT);
            $myMessages->bindValue(':min', $beginning, PDO::PARAM_INT);
            $myMessages->bindValue(':max', $limit, PDO::PARAM_INT);
            $myMessages->execute();

            return $myMessages;
        }
        catch (PDOException $e) {

            $this->flash->add('La connexion ou requête a échoué<br>Informations : ' . $e->getMessage());
        }
    }

    public function createSubject() {

        $idTheme = isset($_POST['theme']) ? htmlspecialchars($_POST['theme']) : null;
        $limit = isset($_POST['limit']) ? htmlspecialchars($_POST['limit']) : null;
        $message = isset($_POST['message']) ? htmlspecialchars($_POST['message']) : null;
        $subject = isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : null;

        // En cas d'absence de JavaScript
        if(strlen($subject) >= 15) {

            if(strlen($subject) <= 255) {

                ucfirst($subject);

                if(strlen($message) >= 15) {

                    if(strlen($message) <= 10000) {

                        ucfirst($message);

                        try {
                            $this->db->sql(
                                'INSERT INTO subjects
                                (
                                    title,
                                    created_at,
                                    id_user,
                                    id_theme
                                )
                                VALUES
                                (
                                    :title,
                                    NOW(),
                                    :id_user,
                                    :id_theme
                                )',
                                [
                                    ':title'    => $subject,
                                    ':id_user'  => $this->userSession->getId(),
                                    ':id_theme' => $idTheme
                                ]
                            );

                            $idSubject = $this->db->queryOne(
                                'SELECT id_subject
                                FROM subjects
                                WHERE title = ?',
                                [$subject]
                            );

                            $this->db->sql(
                                'INSERT INTO messages
                                (
                                    content,
                                    created_at,
                                    id_user,
                                    id_subject
                                )
                                VALUES
                                (
                                    :content,
                                    NOW(),
                                    :id_user,
                                    :id_subject
                                )',
                                [
                                    ':content'    => $message,
                                    ':id_user'    => $this->userSession->getId(),
                                    ':id_subject' => $idSubject['id_subject']
                                ]
                            );
                        }
                        catch (PDOException $e) {

                            $this->flash->add('La connexion ou requête a échoué<br>Informations : ' . $e->getMessage());
                        }

                        $this->flash->add('Votre+sujet+a+été+créé+!');

                        $nbSubjects = $this->allSubjects($idTheme);
                        $nbPages = ceil($nbSubjects / $limit);

                        header('Location:subjects.php?idTheme=' . $idTheme . '&page=' . $nbPages . '&flash=' . $this->flash->lastMessage());
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
                $this->flash->add('Le titre de votre sujet ne peut excéder 255 caractères');
            }
        }
        else {
            $this->flash->add('Le titre de votre sujet doit contenir au moins 15 caractères');
        }
    }

    public function createMessage() {

        $idSubject = isset($_GET['idSubject']) ? htmlspecialchars($_GET['idSubject']) : null;
        $message = isset($_POST['message']) ? htmlspecialchars($_POST['message']) : null;

        // En cas d'absence de JavaScript
        if(strlen($message) >= 15) {

            if(strlen($message) <= 10000) {

                ucfirst($message);

                try {
                    $this->db->sql(
                        'INSERT INTO messages
                        (
                            content,
                            created_at,
                            id_user,
                            id_subject
                        )
                        VALUES
                        (
                            :content,
                            NOW(),
                            :id_user,
                            :id_subject
                        )',
                        [
                            ':content'    => $message,
                            ':id_user'    => $this->userSession->getId(),
                            ':id_subject' => $idSubject
                        ]
                    );
                }
                catch (PDOException $e) {

                    $this->flash->add('La connexion ou requête a échoué<br>Informations : ' . $e->getMessage());
                }

                $this->flash->add('Votre message a été créé !');
            }
            else {
                $this->flash->add('Votre message ne peut excéder 10.000 caractères');
            }
        }
        else {
            $this->flash->add('Votre message doit contenir au moins 15 caractères');
        }
    }
}