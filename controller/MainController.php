<?php

  class Maincontroller{
      protected static $instance;
      protected static $twig;

      private function __construct(){}

      final protected function __clone(){}

      public static function getInstance(){
        if (!static::$instance instanceof static) {
          static::$instance = new static();
          static::$twig = new TwigRenderer();
        }
        return static::$instance;
      }

      public function redirectHome(){
          header("Location: /?action=home", true, 301);
      }

      public function wrongPath(){
          header("Location: /?action=wrong_path", true, 301);
      }

      public function viewHome(){
          $this::$twig->show("home.html");
      }

      public function postElementsCheck($elements){
        $i = 0;
        $i_max = count($elements);
        $ok = ($i < $i_max);
        while($ok && $i < $i_max){
          $key = $elements[$i++];
          $ok = (isset($_POST[$key]) && !empty($_POST[$key]));
        }
        //para debugear
        //
        //   if(!$ok){
        //   echo($elements[--$i]);
        //   die();
        // }
        //
        return $ok;
      }
  }
?>
