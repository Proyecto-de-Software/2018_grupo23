<?php

require_once './vendor/autoload.php';

abstract class TwigView {

    private static $twig;

    public static function getTwig() {

        if (!isset(self::$twig)) {


            $loader = new Twig_Loader_Filesystem('./templates');
            self::$twig = new Twig_Environment($loader, array());
            self::$twig->addFunction(
              new \Twig_SimpleFunction(
                'form_token',
                function($kew_word = null) {
                  if (empty($_SESSION['general_token'])) {
                    $_SESSION['token'] = bin2hex(random_bytes(32));
                  }
                  if (empty($_SESSION['key_token'])) {
                    $_SESSION['key_token'] = random_bytes(32);
                  }
                  if (empty($kew_word)) {
                    return $_SESSION['token'];
                  }
                  return hash_hmac('sha256', $key_word, $_SESSION['key_token']);
                }
              )
          );
        }
        return self::$twig;
    }

}
