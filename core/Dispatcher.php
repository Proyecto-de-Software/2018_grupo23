<?php

/*CONTROLLERS*/
require_once('controller/AppController.php');
require_once('controller/MainController.php');

class Dispatcher{
  static function home(){
    MainController::getInstance()->viewHome();
  }
}




?>
