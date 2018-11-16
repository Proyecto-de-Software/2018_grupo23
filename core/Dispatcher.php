<?php

/*CONTROLLERS*/
require_once('controller/AppController.php');
require_once('controller/MainController.php');
require_once('controller/UserController.php');
require_once('controller/PatientController.php');
require_once('controller/ConfigController.php');
require_once('controller/AttentionController.php');
require_once('controller/APIController.php');

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

  static function paciente_show(){
    PatientController::getInstance()->viewPatient();
  }

  static function paciente_destroy(){
    PatientController::getInstance()->deletePatient();
  }

  static function paciente_update(){
    PatientController::getInstance()->updatePatient();
  }

  static function paciente_index(){
    PatientController::getInstance()->pacienteIndex();
  }

  static function add_nn(){
    PatientController::getInstance()->addNN();
  }

  static function atencion_index(){
    AttentionController::getInstance()->viewAttentionsList();
  }

  static function atencion_new(){
    AttentionController::getInstance()->addAttention();
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

  static function usuario_destroy(){
    UserController::getInstance()->deleteUser();
  }

  static function usuario_update(){
    UserController::getInstance()->updateUser();
  }

  static function usuario_block(){
    UserController::getInstance()->blockUser();
  }

  static function rol_index(){
    ConfigController::getInstance()->viewRolesConfig();
  }

  static function rol_new(){
    ConfigController::getInstance()->addRole();
  }

  static function rol_destroy(){
    ConfigController::getInstance()->deleteRole();
  }

  static function rol_show(){
    ConfigController::getInstance()->viewRole();
  }

  static function rol_update(){
    ConfigController::getInstance()->updateRole();
  }

  static function list_instituciones(){
    APIController::getInstance()->list_instituciones();
  }

}




?>
