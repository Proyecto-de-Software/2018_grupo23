<?php

require_once('model/AttentionRepository.php');

class AttentionController extends MainController{

  protected static $instance;
  protected static $twig;

  function viewAttentionsList($state=NULL, $msg=""){
    if(AppController::getInstance()->checkPermissions($_GET['action'])){
      $repo= new AttentionRepository();
      $tratamientos= $repo->getTratamientos();
      $acomps= $repo->getAcompanamientos();
      $motivos= $repo->getMotivosDeConsulta();
      $params = array('permisos'=>$_SESSION['permissions'], 'tratamientos'=>$tratamientos, 'acomps'=>$acomps, 'motivos'=>$motivos);
      if(!is_null($state)){
        $params[$state]= $msg;
      }
      $this::$twig->show('list_attentions.html', $params);
    }else {
      $this->redirectHome();
    }
  }

}

?>
