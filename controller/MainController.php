<?php

  class Maincontroller{
      private static $instance;
      private static $twig;

      public static function getInstance(){

          if(!isset(self::$instance)){
              self::$instance=new self();
              self::$twig = new TwigRenderer();
          }
          return self::$instance;
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
  }
?>
