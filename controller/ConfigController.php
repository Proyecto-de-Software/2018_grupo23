<?php

require_once('model/ConfigRepository.php');

/**
 * Controlador de la Configuración del sistema
*/
class ConfigController extends MainController {
  protected static $instance;
  protected static $twig;

  public function viewSystemConfig($error = NULL){
    if(!is_null(AppController::getInstance()->getUser())){
      if(AppController::getInstance()->checkPermissions($_GET['action'])){
        $_GET['action']='';
        $param = array();
        if(!is_null($error)){
          $param['error']= $error;
        }
        $param['config']= $this->getConfigParameters();
        $this::$twig->show('config.html', $param);
      }else{
        $this->redirectHome();
      }
    }else{
      $this->redirectHome();
    }
  }

  public function saveConfig(){
    if(!is_null(AppController::getInstance()->getUser())){
      if(AppController::getInstance()->checkPermissions($_GET['action'])){
        if($this->postElementsCheck(array('titulo', 'email', 'descripcion', 'paginado', 'estado'))){
          $query=new ConfigRepository();
          $query->saveConfig($_POST["titulo"],$_POST["email"],$_POST["descripcion"],$_POST["paginado"],$_POST["estado"]);
        }else{
          $this->viewSystemConfig('Hubo un error: debe completar todas las opciones');
        }
      }else{
          $this->redirectHome();
      }
    }$this->redirectHome();
  }

  //obtengo todos los parámetros de configuración del sistema
  public function getConfigParameters(){
    $query= new ConfigRepository();
    $config= $query->getParameters();
    return array('titulo'=>$config[0][2],
                  'email'=>$config[1][2],
                  'descripcion'=>$config[2][2],
                  'paginado'=>$config[3][2],
                  'estado'=>$config[4][2]);
  }

}

?>
