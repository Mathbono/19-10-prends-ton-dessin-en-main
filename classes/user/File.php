<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/prends-ton-dessin-en-main/classes/config/Database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/prends-ton-dessin-en-main/classes/user/connection/StateSession.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/prends-ton-dessin-en-main/classes/config/FlashBag.php';

class File {
    private $db;
    private $userSession;
    private $flash;

    public function __construct() {
        $this->db = new Database();
        $this->userSession = new StateSession();
        $this->flash = new FlashBag();

        if((isset($_POST['submit']) && ($_POST['submit'] === 'Enregistrer un fichier'))) {

            $this->upload();
        }

        if((isset($_POST['submit']) && ($_POST['submit'] === 'Trouver les dessins'))) {

            $this->search();
        }

        if((isset($_POST['submit']) && ($_POST['submit'] === 'Rendre privé'))) {

            $this->private();
        }

        if((isset($_POST['submit']) && ($_POST['submit'] === 'Rendre public'))) {

            $this->public();
        }

        if((isset($_POST['submit']) && ($_POST['submit'] === 'Renommer le dessin'))) {

            $this->rename();
        }

        if((isset($_POST['submit']) && ($_POST['submit'] === 'Ajouter/Modifier le commentaire'))) {

            $this->comment();
        }

        if((isset($_POST['submit']) && ($_POST['submit'] === 'Télécharger le dessin'))) {

            $this->download();
        }

        if((isset($_POST['submit']) && ($_POST['submit'] === 'Télécharger le commentaire'))) {

            $this->download();
        }

        if((isset($_POST['submit']) && ($_POST['submit'] === 'Supprimer le commentaire'))) {

            $this->clean();
        }

        if((isset($_POST['submit']) && ($_POST['submit'] === 'Tout supprimer'))) {

            $this->suppress();
        }
    }

    public function stristr_array($array, $name) {

        foreach($array as $element) {

            if(stristr($name, '.', true) === stristr($element, '.', true)) {

                return true;
            }
        }
    }

    public function save($picture, $title) {

        if((isset($picture)) && (isset($title))) {

            $title = ucfirst($title);
            $compTitle = $title . '.png';
            $dirPicture = '../img/users/drawings/' . $this->userSession->getId() . '/';
            $dirComment = '../doc/users/drawings/' . $this->userSession->getId() . '/';
            $contentDirPicture = array_diff(scandir($dirPicture), array('..', '.', 'private'));
            $contentDirComment = array_diff(scandir($dirComment), array('..', '.'));
            $element = $this->stristr_array($contentDirPicture, $compTitle);

            if(!in_array($compTitle, $contentDirPicture)) {

                if($element !== true) {

                    // En cas d'absence de JavaScript
                    if ((stristr($title, '/') === false)
                        && (stristr($title, '\\') === false)
                        && (stristr($title, '+') === false)
                        && (stristr($title, ':') === false)
                        && (stristr($title, '*') === false)
                        && (stristr($title, '?') === false)
                        && (stristr($title, '"') === false)
                        && (stristr($title, '<') === false)
                        && (stristr($title, '>') === false)
                        && (stristr($title, '|') === false)
                        && (stristr($title, '.') === false)) {

                        if (!empty($title)) {

                            if (strlen($title) <= 100) {

                                $picture = explode('base64,', $picture);
                                $file = $picture[1];
                                $fileComment = $title . '.txt';

                                if(!in_array($fileComment, $contentDirComment)) {

                                    file_put_contents($dirPicture . $compTitle, base64_decode($file));
                                    file_put_contents($dirComment . $fileComment, '');
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function upload() {

        $tmpFile = $_FILES['userfile']['tmp_name'];

        if(is_uploaded_file($tmpFile)) {

            $dirPicture = isset($_POST['dirPicture']) ? htmlspecialchars($_POST['dirPicture']) : null;
            $dirComment = isset($_POST['dirComment']) ? htmlspecialchars($_POST['dirComment']) : null;
            $name = basename($_FILES['userfile']['name']);
            $name = ucfirst($name);
            $compDir = $dirPicture . $name;
            if(file_exists($compDir)) {
                $file = pathinfo($compDir, PATHINFO_FILENAME);
            }
            $file = isset($file) ? $file : null;
            $diff = strcasecmp(stristr($name, '.', true), $file);

            $contentDirPicture = array_diff(scandir($dirPicture), array('..', '.', 'private'));
            $contentDirComment = array_diff(scandir($dirComment), array('..', '.'));

            $element = $this->stristr_array($contentDirPicture, $name);

            if(($diff === strlen(stristr($name, '.', true))) && ($element !== true)) {

                if((stristr($name, '/') === false)
                    && (stristr($name, '\\') === false)
                    && (stristr($name, '+') === false)
                    && (stristr($name, ':') === false)
                    && (stristr($name, '*') === false)
                    && (stristr($name, '?') === false)
                    && (stristr($name, '"') === false)
                    && (stristr($name, '<') === false)
                    && (stristr($name, '>') === false)
                    && (stristr($name, '|') === false)) {

                    if(strlen($name) <= 100) {

                        if((strpos($name, '.') === strlen($name) - 4) || (strpos($name, '.') === strlen($name) - 5)) {

                            if(stristr($name, '.', true) !== '') {

                                if(preg_match("/.gif$|.ico$|.jpg$|.jpeg$|.png$|.pdf$|.svg$|.tif$|.tiff$|.webp$/i", $name)) {

                                    $fileComment = stristr($name, '.', true) . '.txt';

                                    if(!in_array($fileComment, $contentDirComment)) {

                                        if(move_uploaded_file($tmpFile, $compDir)) {

                                            file_put_contents($dirComment . $fileComment, '');

                                            $this->flash->add('Votre dessin est ajouté à vos dessins !');
                                        }
                                    }
                                    else {
                                        $this->flash->add('Vous devez mettre un autre titre pour votre dessin');
                                    }
                                }
                                else {
                                    $this->flash->add('Vous ne pouvez pas enregistrer autre chose qu\'une image');
                                }
                            }
                            else {
                                $this->flash->add('Vous devez mettre un titre pour votre dessin');
                            }
                        }
                        else {
                            $this->flash->add('Vous ne pouvez mettre de point dans votre titre mis à part l\'extension, laquelle contient obligatoirement trois ou quatre caractères');
                        }
                    }
                    else {
                        $this->flash->add('Le titre que vous avez choisi pour votre dessin est trop long');
                    }
                }
                else {
                    $this->flash->add('Un nom de fichier ne peut pas contenir les caractères suivants : / \ + * ? " < > | :');
                }
            }
            else {
                $this->flash->add('Un fichier du même nom existe déjà');
            }
        }
        else {
            switch($_FILES['userfile']['error']) {
                case 3:
                    $_FILES['userfile']['error'] = 'le fichier n\'a été que partiellement téléchargé';
                    break;
                case 4:
                    $_FILES['userfile']['error'] = 'aucun fichier n\'a été téléchargé';
                    break;
                default:
                    $_FILES['userfile']['error'] = 'problème côté serveur';
            }

            $this->flash->add('Le fichier n\'est pas valide. Erreur : '
                . $_FILES['userfile']['error']);
        }
    }

    public function download() {

        $dirPicture = isset($_POST['dirPicture']) ? htmlspecialchars($_POST['dirPicture']) : null;
        $picture = isset($_POST['picture']) ? htmlspecialchars($_POST['picture']) : null;
        $dirPicture .= $picture;
        $sizePicture = filesize($dirPicture);

        $dirComment = isset($_POST['dirComment']) ? htmlspecialchars($_POST['dirComment']) : null;
        $comment = isset($_POST['comment']) ? htmlspecialchars($_POST['comment']) : null;
        $dirComment .= $comment;
        $sizeComment = filesize($dirComment);

        if(!empty($dirPicture)) {
            $dir = $dirPicture;
            $file = $picture;
            $size = $sizePicture;
            $type = 'application/force-download';
        }
        else if(!empty($dirComment)) {
            $dir = $dirComment;
            $file = $comment;
            $size = $sizeComment;
            $type = 'text/plain\n';
        }

        header('Content-Type: ' . $type . '; name="' . $file . '"');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . $size);
        header('Content-Disposition: attachment; filename="' . $file . '"');
        header('Expires: 0');
        header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        header('Pragma: no-cache');
        readfile($dir);
        exit();
    }

    public function search() {

        $user = isset($_POST['user']) ? htmlspecialchars($_POST['user']) : null;

        try {
            // Vérifier si le login est présent dans la base
            $res = $this->db->queryOne(
                'SELECT id_user
                FROM users
                WHERE login = ?',
                [$user]
            );
        }
        catch (PDOException $e) {

            $this->flash->add('La connexion ou requête a échoué<br>Informations : ' . $e->getMessage());
        }

        // Si le login est présent en base, continuer
        if($res !== false) {
            header('Location:searchdrawings.php?id=' . $res['id_user']);
        }
        else {
            $this->flash->add('Ce pseudo n\'existe pas');
        }
    }

    public function private() {

        $dirPicture = isset($_POST['dirPicture']) ? htmlspecialchars($_POST['dirPicture']) : null;
        $picture = isset($_POST['picture']) ? htmlspecialchars($_POST['picture']) : null;

        copy($dirPicture . $picture, $dirPicture . 'private/' . $picture);

        $this->flash->add('Le dessin est maintenant privé !');
    }

    public function public() {

        $dirPicture = isset($_POST['dirPicture']) ? htmlspecialchars($_POST['dirPicture']) : null;
        $picture = isset($_POST['picture']) ? htmlspecialchars($_POST['picture']) : null;

        @unlink($dirPicture . 'private/' . $picture);

        $this->flash->add('Le dessin est maintenant public !');
    }

    public function rename() {

        $dirPicture = isset($_POST['dirPicture']) ? htmlspecialchars($_POST['dirPicture']) : null;
        $dirComment = isset($_POST['dirComment']) ? htmlspecialchars($_POST['dirComment']) : null;
        $picture = isset($_POST['picture']) ? htmlspecialchars($_POST['picture']) : null;
        $name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : null;

        $contentDirPicture = array_diff(scandir($dirPicture), array('..', '.', 'private'));
        $contentDirComment = array_diff(scandir($dirComment), array('..', '.'));

        if(!empty($name)) {

            $name = ucfirst($name);

            if((stristr($name, '/') === false)
                && (stristr($name, '\\') === false)
                && (stristr($name, '+') === false)
                && (stristr($name, ':') === false)
                && (stristr($name, '*') === false)
                && (stristr($name, '?') === false)
                && (stristr($name, '"') === false)
                && (stristr($name, '<') === false)
                && (stristr($name, '>') === false)
                && (stristr($name, '|') === false)
                && (stristr($name, '.') === false)) {

                $fileComment = $name . '.txt';
                $name .= stristr($picture, '.');

                if(!in_array($name, $contentDirPicture)) {

                    $element = $this->stristr_array($contentDirPicture, $name);

                    if($element !== true) {

                        if(strlen($name) <= 100) {

                            if(!in_array($fileComment, $contentDirComment)) {

                                rename($dirPicture . $picture, $dirPicture . $name);
                                rename($dirComment . stristr($picture, '.', true) . '.txt', $dirComment . $fileComment);

                                $this->flash->add('Le dessin a été renommé !');
                            }
                            else {
                                $this->flash->add('Vous devez mettre un autre titre pour votre dessin');
                            }
                        }
                        else {
                            $this->flash->add('Le titre que vous avez choisi pour votre dessin est trop long');
                        }
                    }
                    else {
                        $this->flash->add('Un fichier du même nom existe déjà');
                    }
                }
                else {
                    $this->flash->add('Un fichier du même nom existe déjà');
                }
            }
            else {
                $this->flash->add('Un nom de fichier ne peut pas contenir les caractères suivants : / \ + * ? " < > | : '
                    . 'Vous ne pouvez pas non plus mettre de point dans votre titre car il n\'est pas nécessaire d\'indiquer l\'extension');
            }
        }
        else {
            $this->flash->add('Vous devez mettre un titre pour votre dessin');
        }
    }

    public function comment() {

        $dirComment = isset($_POST['dirComment']) ? htmlspecialchars($_POST['dirComment']) : null;
        $picture = isset($_POST['picture']) ? htmlspecialchars($_POST['picture']) : null;
        $comment = isset($_POST['comment']) ? htmlspecialchars($_POST['comment']) : null;

        if (!empty($comment)) {

            if(stristr($comment, '+') === false) {

                if (strlen($comment) >= 3) {

                    $comment = ucfirst($comment);

                    if (strlen($comment) <= 10000) {

                        file_put_contents($dirComment . stristr($picture, '.', true) . '.txt', $comment);
                        $this->flash->add('Le dessin a été commenté !');
                    }
                    else {
                        $this->flash->add('Le commentaire pour votre dessin est trop long');
                    }
                }
                else {
                    $this->flash->add('Le commentaire pour votre dessin doit comporter au moins 3 caractères');
                }
            }
            else {
                $this->flash->add('Le commentaire ne peut pas contenir le caractère +');
            }
        }
        else {
            $this->flash->add('Vous n\'avez pas mis de commentaire pour votre dessin');
        }
    }

    public function clean() {

        $dirComment = isset($_POST['dirComment']) ? htmlspecialchars($_POST['dirComment']) : null;
        $comment = isset($_POST['comment']) ? htmlspecialchars($_POST['comment']) : null;

        file_put_contents($dirComment . $comment, '');

        $this->flash->add('Le commentaire a été supprimé !');
    }

    public function suppress() {

        $dirPicture = isset($_POST['dirPicture']) ? htmlspecialchars($_POST['dirPicture']) : null;
        $dirComment = isset($_POST['dirComment']) ? htmlspecialchars($_POST['dirComment']) : null;
        $picture = isset($_POST['picture']) ? htmlspecialchars($_POST['picture']) : null;
        $comment = isset($_POST['comment']) ? htmlspecialchars($_POST['comment']) : null;

        @unlink($dirPicture . $picture);
        @unlink($dirComment . $comment);

        $this->flash->add('Le dessin a été supprimé !');
    }
}