<?php

require_once('model/ConfigRepository.php');

/**
 * Controlador de la Configuración del sistema
*/
class ConfigController extends MainController {
  protected static $instance;
  protected static $twig;

  public function viewSystemConfig($state= NULL, $msg=""){
    if(!is_null(AppController::getInstance()->getUser())){
      if(AppController::getInstance()->checkPermissions($_GET['action'])){
        $_GET['action']='';
        $param = array();
        if(!is_null($state)){
          $param[$state]= $msg;
        }
        $this::$twig->show('config.html', $param);
      }else{//no tiene permiso para realizar esta acción
        $this->redirectHome();
      }
    }else{//no es un usuario logueado
      $this->redirectHome();
    }
  }

  public function saveConfig(){
    if(!is_null(AppController::getInstance()->getUser())){
      if(AppController::getInstance()->checkPermissions($_GET['action'])){
        if($this->postElementsCheck(array('titulo', 'email', 'descripcion', 'paginado', 'estado'))){
          $this->prepareData(array('titulo', 'email', 'descripcion', 'paginado', 'estado',
                                   'col1_text', 'col2_text', 'col3_text',
                                   'col1_title', 'col2_title', 'col3_title'));
          $query=new ConfigRepository();
          $query->saveConfig($_POST["titulo"],$_POST["email"],$_POST["descripcion"],$_POST["paginado"],$_POST["estado"],$_POST["col1_text"],$_POST["col2_text"],$_POST["col3_text"],
                                $_POST["col1_title"],$_POST["col2_title"],$_POST["col3_title"]);
          $this->viewSystemConfig('success', 'Configuración guardada');
        }else{
          $this->viewSystemConfig('error', 'Se produjo un error: debe completar todas las opciones');
        }
      }else{
          $this->redirectHome();
      }
    }else{
      $this->redirectHome();
    }
  }

  //obtengo todos los parámetros de configuración del sistema
  public function getConfigParameters(){
    $query= new ConfigRepository();
    $config= $query->getParameters();
    return array('titulo'=>$config[0][2],
                  'email'=>$config[1][2],
                  'descripcion'=>$config[2][2],
                  'estado'=>$config[3][2],
                  'paginado'=>$config[4][2],
                  'columna_uno'=>$config[5][2],
                  'columna_dos'=>$config[6][2],
                  'columna_tres'=>$config[7][2],
                  'titulo_col_uno'=>$config[8][2],
                  'titulo_col_dos'=>$config[9][2],
                  'titulo_col_tres'=>$config[10][2]);
  }

}

?>
