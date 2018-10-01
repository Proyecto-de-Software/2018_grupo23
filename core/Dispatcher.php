<?php

/*CONTROLLERS*/
require_once('controller/AppController.php');
require_once('controller/MainController.php');
require_once('controller/UserController.php');
require_once('controller/PatientController.php');
require_once('controller/ConfigController.php');

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

  static function view_patient(){
    PatientController::getInstance()->viewPatient();
  }

  static function view_system_config(){
    ConfigController::getInstance()->viewSystemConfig();
  }

  static function save_config(){
    ConfigController::getInstance()->saveConfig();
  }

  static function view_users_list(){
    UserController::getInstance()->viewUsersList();
  }
}




?>
