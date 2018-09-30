<?php

require_once('model/ConfigRepository.php');

/**
 * Controlador de la Configuración del sistema
*/
class ConfigController extends MainController {
  protected static $instance;
  protected static $twig;

  public function viewSystemConfig(){
    $query= new ConfigRepository();
    $config= $query->getConfig();
    $param= array('config'=>$config);
    $this::$twig->show('config.html', $param);
  }

  public function saveConfig(){
    $query=new ConfigRepository();
//    $array= array('titulo', 'email')
    $query->saveConfig($_POST["titulo"],$_POST["email"],$_POST["descripcion"],$_POST["cant_elems"],$_POST["estado"]);
    $this->redirectHome();
  }

}

?>
