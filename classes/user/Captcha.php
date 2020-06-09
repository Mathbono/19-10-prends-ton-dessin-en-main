<?php

class Captcha {

    public function dictionary() {

        $list = file($_SERVER['DOCUMENT_ROOT'] . '/prends-ton-dessin-en-main/doc/dictionary/list_22740.txt');
        return trim($list[array_rand($list)]);
    }

    public function displayCaptcha() {

        $word = $this->dictionary();
        $_SESSION['captcha'] = $word;

        return $word;
    }
}