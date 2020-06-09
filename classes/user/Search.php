<?php

class Search {
    private $app;
    private $db;
    private $flash;
    private $userSession;

    public function __construct() {
        $this->app = '../../app/';
        $this->db = new Database();
        $this->flash = new FlashBag();
        $this->userSession = new StateSession();

        // Avons-nous une session ?
        if(session_status() == PHP_SESSION_NONE) {

            // Si non, créons-là
            session_start();
        }

        if((isset($_POST['searchBlog']) && ($_POST['searchBlog'] === 'Chercher'))) {

            $word = $this->secureWord();

            if($word !== false) {

                $res = $this->searchBlog($word);
                $this->displaySearch($res);
            }
        }

        if((isset($_POST['searchMyDrawings']) && ($_POST['searchMyDrawings'] === 'Chercher'))) {

            $word = $this->secureWord();

            if($word !== false) {

                $res = $this->searchMyDrawings($word);
                $this->displaySearch($res);
            }
        }

        if((isset($_POST['searchForum']) && ($_POST['searchForum'] === 'Chercher'))) {

            $word = $this->secureWord();

            if($word !== false) {

                $res = $this->searchForum($word);
                $this->displaySearch($res);
            }
        }

        if((isset($_POST['searchSubjects']) && ($_POST['searchSubjects'] === 'Chercher'))) {

            $word = $this->secureWord();

            if($word !== false) {

                $res = $this->searchSubjects($word);
                $this->displaySearch($res);
            }
        }

        if((isset($_POST['searchMessages']) && ($_POST['searchMessages'] === 'Chercher'))) {

            $word = $this->secureWord();

            if($word !== false) {

                $res = $this->searchMessages($word);
                $this->displaySearch($res);
            }
        }

        if((isset($_POST['searchMySubjects']) && ($_POST['searchMySubjects'] === 'Chercher'))) {

            $word = $this->secureWord();

            if($word !== false) {

                $res = $this->searchMySubjects($word);
                $this->displaySearch($res);
            }
        }

        if((isset($_POST['searchMyMessages']) && ($_POST['searchMyMessages'] === 'Chercher'))) {

            $word = $this->secureWord();

            if($word !== false) {

                $res = $this->searchMyMessages($word);
                $this->displaySearch($res);
            }
        }
    }

    public function secureWord() {

        $word = isset($_POST['word']) ? htmlspecialchars($_POST['word']) : null;

        if(!empty($word)) {

            // Pour supprimer les balises HTML dans la requête
            $word = strip_tags($word);

            // Pour tout mettre en minuscule
            $word = strtolower($word);

            return $word;
        }
        else {
            $this->flash->add('Votre recherche est invalide');

            return false;
        }
    }

    public function displaySearch($res) {

        $_SESSION['search'] = $res;
        header('Location:' . $this->app . 'search.php');
    }

    public function searchBlog($word) {

        $dirComment = '../../doc/drawings/';
        $contentDirComment = array_diff(scandir($dirComment), array('..', '.'));
        sort($contentDirComment, SORT_NATURAL | SORT_FLAG_CASE);
        $nbComments = count($contentDirComment);

        for($i = 1; $i <= $nbComments; $i++) {

            $content = file_get_contents($dirComment . $i . '.txt');
            $search = stripos($content, $word);

            if ($search !== false) {
                $res[$i - 1]['content'] = $content;
            }
            else {
                $res[$i - 1]['content'] = '';
            }
        }

        return $res;
    }
    public function searchMyDrawings($word) {

        $dirComment = '../../doc/users/drawings/' . $this->userSession->getId() . '/';
        $contentDirComment = array_diff(scandir($dirComment), array('..', '.'));
        sort($contentDirComment, SORT_NATURAL | SORT_FLAG_CASE);
        $i = 0;

        foreach($contentDirComment as $comment) {

            $content = file_get_contents($dirComment . $comment);
            $search = stripos($content, $word);

            if ($search !== false) {
                $res[$i]['content'] = $content;
            }
            else {
                $res[$i]['content'] = '';
            }

            $i++;
        }

        return $res;
    }

    public function searchForum($word) {

        try {
            $res = $this->db->queryAll(
                'SELECT title
                FROM subjects
                WHERE title LIKE ?
                UNION
                SELECT content
                FROM messages
                WHERE content LIKE ?',
                ['%' . $word . '%', '%' . $word . '%']
            );

            return $res;
        }
        catch (PDOException $e) {

            $this->flash->add('La connexion ou requête a échoué<br>Informations : ' . $e->getMessage());
        }
    }

    public function searchSubjects($word) {

        try {
            $res = $this->db->queryAll(
                'SELECT title
                FROM subjects
                WHERE title LIKE ?',
                ['%' . $word . '%']
            );

            return $res;
        }
        catch (PDOException $e) {

            $this->flash->add('La connexion ou requête a échoué<br>Informations : ' . $e->getMessage());
        }
    }

    public function searchMessages($word) {

        try {
            $res = $this->db->queryAll(
                'SELECT content
                FROM messages
                WHERE content LIKE ?',
                ['%' . $word . '%']
            );

            return $res;
        }
        catch (PDOException $e) {

            $this->flash->add('La connexion ou requête a échoué<br>Informations : ' . $e->getMessage());
        }
    }

    public function searchMySubjects($word) {

        try {
            $idUser = $this->userSession->getId();

            $res = $this->db->queryAll(
                'SELECT title
                FROM subjects
                WHERE id_user = :idUser
                  AND title LIKE :title',
                [
                    ':idUser' => $idUser,
                    ':title' => '%' . $word . '%'
                ]
            );

            return $res;
        }
        catch (PDOException $e) {

            $this->flash->add('La connexion ou requête a échoué<br>Informations : ' . $e->getMessage());
        }
    }

    public function searchMyMessages($word) {

        try {
            $idUser = $this->userSession->getId();

            $res = $this->db->queryAll(
                'SELECT content
                FROM messages
                WHERE id_user = :idUser
                  AND content LIKE :content',
                [
                    ':idUser' => $idUser,
                    ':content' => '%' . $word . '%'
                ]
            );

            return $res;
        }
        catch (PDOException $e) {

            $this->flash->add('La connexion ou requête a échoué<br>Informations : ' . $e->getMessage());
        }
    }
}