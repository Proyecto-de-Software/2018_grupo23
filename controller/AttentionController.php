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
      $api_repo= new APIRepository();
      $instituciones= $api_repo->getAllInstitutions();
      $params = array('permisos'=>$_SESSION['permissions'], 'id_paciente'=>$id_paciente_local, 'instituciones'=> $instituciones, 'tratamientos'=>$tratamientos, 'acomps'=>$acomps, 'motivos'=>$motivos);
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
          $this->prepareData(array('motivo', 'derivacion', 'articulacion', 'fecha_consulta', 'internacion', 'diag', 'obs', 'trat', 'acomp'));
          $err= $this->isValidForm($_POST['fecha_consulta']);
          $id_paciente= $_POST['id_paciente'];
          if(empty($err)){
            $repo= new AttentionRepository();
            $repo->newAttention($id_paciente, $_POST['derivacion'], $_POST['motivo'], $_POST['articulacion'], $_POST['fecha_consulta'], $_POST['internacion'], $_POST['diag'], $_POST['obs'], $_POST['trat'], $_POST['acomp']);
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

  public function viewAttentionsBy($result){//engloba lo común a AttentionsByReason y ByGenre
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

  public function viewAttentionsByLocation(){//utiliza la Api de localidades 
    $repo= new AttentionRepository();
    $result= $repo->getAtencionesPorLocalidad();
    $localidades=json_decode(@file_get_contents('https://api-referencias.proyecto2018.linti.unlp.edu.ar/localidad'), true);
    $loc_array=array();
    foreach ($localidades as $key => $value) { //creo un array que me sirve para pasarle el nombre a los dataPoints
      $loc_array[$value['id']] = $value['nombre'];
    }
    $dataPoints=array();
    foreach($result as $row){
        $loc_nombre= $row->id == 0 ? 'Sin localidad asignada' : $loc_array[$row->id];
        array_push($dataPoints, array('y'=> $row->porcentaje, 'label'=> $loc_nombre, 'cant'=> $row->cant));
    }
    echo json_encode($dataPoints, JSON_NUMERIC_CHECK);
  }


}

?>
