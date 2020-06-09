<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/prends-ton-dessin-en-main/classes/config/Database.php';

class Visited {
    private $db;
    private $flash;
    private $data;

    public function __construct() {
        $this->db = new Database();
        $this->flash = new FlashBag();

        try {
            // On vérifie si l'IP se trouve déjà dans la table
            // On compte le nombre d'entrées dont le champ "ip" est l'adresse IP du visiteur
            $data = $this->db->querySimpleOne('
                SELECT COUNT(*)
                AS nbr_visited
                FROM visited
                WHERE ip_address = ' . $_SERVER['REMOTE_ADDR']
            );

            // Si l'IP ne se trouve pas dans la table, on va l'ajouter
            if ($data['nbr_visited'] === 0) {
                $this->db->simpleSql('
                    INSERT INTO visited(
                        ip_address,
                        visited_at
                )
                VALUES('
                    . $_SERVER['REMOTE_ADDR'] .
                    ', NOW()'
                );

            // Si l'IP se trouve déjà dans la table, on met juste à jour le datetime
            } else {
                $this->db->simpleSql('UPDATE visited
                    SET visited_at = NOW()
                    WHERE ip_address = ' . $_SERVER['REMOTE_ADDR']
                );
            }

            // On compte le nombre d'IP stockées dans la table. C'est le nombre de visiteurs connectés
            $data = $this->db->queryOne('SELECT COUNT(*)
                AS nbr_visited
                FROM visited'
            );

            $this->setData($data);
        }
        catch (PDOException $e) {

            $this->flash->add('La connexion ou requête a échoué<br>Informations : ' . $e->getMessage());
        }
    }

    public function getData(): array {
        return $this->data;
    }

    public function setData(array $data): void {
        $this->data = $data;
    }
}