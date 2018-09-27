<?php

/*CONTROLLERS*/
require_once('controller/AppController.php');
require_once('controller/MainController.php');
require_once('controller/UserController.php');

class Dispatcher{
  static function home(){
    MainController::getInstance()->viewHome();
  }

  static function view_login(){
    UserControler::getInstance()->viewLogin();
  }

  static function login(){
    UserControler::getInstance()->login();
  }
}




?>
