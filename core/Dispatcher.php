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


  static function paciente_new(){
    PatientController::getInstance()->addPatient();
  }

  static function view_patient(){
    PatientController::getInstance()->viewPatient();
  }

  static function configuracion_index(){
    ConfigController::getInstance()->viewSystemConfig();
  }

  static function configuracion_update(){
    ConfigController::getInstance()->saveConfig();
  }

  static function usuario_index(){
    UserController::getInstance()->viewUsersList();
  }

  static function usuario_new(){
    UserController::getInstance()->addUser();
  }

  static function usuario_show(){
    UserController::getInstance()->viewUser();
  }

  static function paciente_index(){
    PatientController::getInstance()->viewPatientList();
  }

  static function add_nn(){
    PatientController::getInstance()->addNN();
  }
}




?>
