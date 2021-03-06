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
          $ok = (isset($_POST[$key]) && (!empty($_POST[$key]) || is_numeric($_POST[$key])));
        }
        // //para debugear
        //
        //   if(!$ok){
        //   echo($elements[--$i]);
        //   die();
        //}

        return $ok;
      }

      public function checkToken($key_word = NULL){
        if (!is_null($_POST['token']) && !empty($_POST['token'])) {
          if(!is_null($key_word)){
            $calc = hash_hmac('sha256', $key_word, $_SESSION['key_token']);
            if (hash_equals($calc, $_POST['token'])) {
              return true;
            }
          }
          else{
            if (hash_equals($_SESSION['general_token'], $_POST['token'])) {
              return true;
            }
            else {
              return false;
            }
          }
        } return false;
      }
    	public function prepareData($elements) {
        foreach($elements as $key){
          if(isset($_POST[$key])){
				    $_POST[$key] = trim($_POST[$key]); //Elimina espacio en blanco (u otro tipo de caracteres) del inicio y el final de la cadena
				    $_POST[$key] = strip_tags($_POST[$key]); //Retira las etiquetas HTML y PHP de un string
				    $_POST[$key] = addslashes($_POST[$key]); //Escapa un string con barras invertidas
				    $_POST[$key] = stripslashes($_POST[$key]); //Quita las barras de un string con comillas escapadas
            $_POST[$key] = htmlspecialchars($_POST[$key]); //Convierte caracteres especiales en entidades HTML
          }
        }
		}
  }
?>
