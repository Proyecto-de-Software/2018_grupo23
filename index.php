<?php

/*TWIG*/
require_once('core/TwigView.php');
require_once('core/TwigRenderer.php');
/*DISPATCHER*/
require_once('core/Dispatcher.php');
/*REPOSITORY*/
require_once('core/Connection.php');
/*CONTROLLER*/
require_once('controller/ConfigController.php');
require_once('controller/AppController.php');

DEFINE('DS', DIRECTORY_SEPARATOR); //separador para multiples OS

session_start();

$action=isset($_GET['action'])? $_GET['action'] :'home'; /* si el action esta seteado asigno el valor del get al action y sino es home */
//Dispatcher::$action();
try{
if(ConfigController::getInstance()->getConfigParameters()['estado'] == "habilitado"){
  Dispatcher::$action();
}
else{
  if(AppController::getInstance()->getUser() && in_array("Administrador",AppController::getInstance()->getUserData()['roles'])){
    Dispatcher::$action();
  }
  else if($action == 'login'){
    Dispatcher::$action();
  }
  else{
    AppController::getInstance()->endUserSession();
    Dispatcher::view_login();
  }
}

}

catch (Throwable $t) {
    Dispatcher::home();
}

?>
