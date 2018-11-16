<?php

require_once('model/AttentionRepository.php');

class AttentionController extends MainController{

  protected static $instance;
  protected static $twig;

  function viewAttentionsList($state=NULL, $msg="", $id_paciente=NULL){
    if(AppController::getInstance()->checkPermissions('atencion_index')){
      $repo= new AttentionRepository();
      $id_paciente_local= isset($_GET['id_paciente']) ? $_GET['id_paciente'] : $id_paciente;
      $tratamientos= $repo->getTratamientos();
      $acomps= $repo->getAcompanamientos();
      $motivos= $repo->getMotivosDeConsulta();
      $params = array('permisos'=>$_SESSION['permissions'], 'id_paciente'=>$id_paciente_local, 'tratamientos'=>$tratamientos, 'acomps'=>$acomps, 'motivos'=>$motivos);
      if(!is_null($state)){
        $params[$state]= $msg;
      }
      $this::$twig->show('list_attentions.html', $params);
    }else {
      $this->redirectHome();
    }
  }

  //faltan validaciones
  function isValidForm($fecha_consulta){
    $err='';
    if($this->postElementsCheck(array('motivo', 'fecha_consulta', 'internacion', 'diag', 'trat', 'acomp'))){
      if ($fecha_consulta < date('Y-m-d')){
        $err.= 'error con la fecha ingresada: la fecha no puede ser anterior a hoy';
      }
    }else {
      $err.= 'Se produjo un error: faltó completar alguno/s de los datos. ';
    }
    return $err;
  }

  function addAttention(){
    if(!is_null(AppController::getInstance()->getUser())){
      if(AppController::getInstance()->checkPermissions($_GET['action'])){
        if($this->checkToken('atencion_new')){
          //recordar que falta el $_POST de la institución
          $this->prepareData(array('motivo', 'articulacion', 'fecha_consulta', 'internacion', 'diag', 'obs', 'trat', 'acomp'));
          $err= $this->isValidForm($_POST['fecha_consulta']);
          $id_paciente= $_POST['id_paciente'];
          if(empty($err)){
            $repo= new AttentionRepository();
            $repo->newAttention($id_paciente, $_POST['motivo'], $_POST['articulacion'], $_POST['fecha_consulta'], $_POST['internacion'], $_POST['diag'], $_POST['obs'], $_POST['trat'], $_POST['acomp']);
            $this->viewAttentionsList('success', 'Atención agregada', $id_paciente);
          }else {
            $this->viewAttentionsList('error', $err, $id_paciente);
          }
        }else {
          $this->viewAttentionsList('error', 'Error con la validación del Token', $id_paciente);
        }
      }else {
        $this->viewAttentionsList('error', 'Se produjo un error: no tienes permiso para realizar esa acción', $id_paciente);
      }
    }else {
      $this->redirectHome();
    }
  }

  /*reportes*/

  public function viewAttentionsReport(){
    $this::$twig->show('report.html');
  }

  public function viewAttentionsBy($result){
    $dataPoints=array();
    foreach($result as $row){
        array_push($dataPoints, array('y'=> $row->porcentaje, 'label'=> $row->nombre, 'cant'=> $row->cant));
    }
    echo json_encode($dataPoints, JSON_NUMERIC_CHECK);
  }

  public function viewAttentionsByReason(){
    $repo= new AttentionRepository();
    $this->viewAttentionsBy($repo->getAtencionesPorMotivo());
  }

  public function viewAttentionsByGenre(){
    $repo= new AttentionRepository();
    $this->viewAttentionsBy($repo->getAtencionesPorGenero());
  }

  public function viewAttentionsByLocation(){
    $repo= new AttentionRepository();
    $this->viewAttentionsBy($repo->getAtencionesPorLocalidad());
  }


}

?>
