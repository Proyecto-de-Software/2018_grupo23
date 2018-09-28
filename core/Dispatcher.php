<?php

/*CONTROLLERS*/
require_once('controller/AppController.php');
require_once('controller/MainController.php');
require_once('controller/UserController.php');
require_once('controller/PatientController.php');

class Dispatcher{
  static function home(){
    MainController::getInstance()->viewHome();
  }

  static function view_login(){
    UserController::getInstance()->viewLogin();
  }

  static function login(){
    UserController::getInstance()->login();
  }

  static function logout(){
    UserController::getInstance()->logout();
  }

  static function view_add_patient(){
    PatientController::getInstance()->viewAddPatient();
  }

  static function add_patient(){
    PatientController::getInstance()->addPatient();
  }
}




?>
