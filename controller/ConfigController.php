<?php

require_once('model/ConfigRepository.php');

/**
 * Controlador de la Configuración del sistema
*/
class ConfigController extends MainController {
  protected static $instance;
  protected static $twig;

  public function viewSystemConfig($error = NULL){
    $_GET['action']='';
    $param = array();
    if(!is_null($error)){
      $param['error']= $error;
    }
    $query= new ConfigRepository();
    $config= $query->getConfig();
    $param['config']= array('titulo'=>$config[0][2],
                            'email'=>$config[1][2],
                            'descripcion'=>$config[2][2],
                            'cant_elems'=>$config[3][2],
                            'estado'=>$config[4][2]);
    $this::$twig->show('config.html', $param);
  }

  public function saveConfig(){
    if($this->postElementsCheck(array('titulo', 'email', 'descripcion', 'cant_elems', 'estado'))){
      $query=new ConfigRepository();
      $query->saveConfig($_POST["titulo"],$_POST["email"],$_POST["descripcion"],$_POST["cant_elems"],$_POST["estado"]);
      $this->redirectHome();
    }else{
      $this->viewSystemConfig('Hubo un error: debe completar todas las opciones');
    }
  }

}

?>
