<?php

require_once('model/AttentionRepository.php');
require_once('model/APIRepository.php');

class AttentionController extends MainController{

  protected static $instance;
  protected static $twig;

  function viewAttentionsList($state=NULL, $msg=""){
    if(AppController::getInstance()->checkPermissions($_GET['action'])){
      if(isset($_POST['id_paciente'])){
        $repo= new AttentionRepository();
        $tratamientos= $repo->getTratamientos();
        $acomps= $repo->getAcompanamientos();
        $motivos= $repo->getMotivosDeConsulta();
        $api_repo= new APIRepository();
        $instituciones= $api_repo->getAllInstitutions();
        $params = array('permisos'=>$_SESSION['permissions'], 'id_paciente'=>$_POST['id_paciente'], 'fecha_hoy'=>date('Y-m-d'), 'instituciones'=> $instituciones, 'tratamientos'=>$tratamientos, 'acomps'=>$acomps, 'motivos'=>$motivos);
        if(!is_null($state)){
          $params[$state]= $msg;
        }
        $this::$twig->show('list_attentions.html', $params);
      }else {
        $this->redirectHome();
      }
    }else {
      $this->redirectHome();
    }
  }

  //faltan validaciones
  function isValidForm($fecha, $derivacion, $motivo, $art, $internacion, $diag, $obs, $trat, $acomp){
    $err='';
    if($this->postElementsCheck(array('fecha_consulta', 'motivo', 'internacion', 'diag'))){
      $api_repo= new APIRepository();
      $instituciones= $api_repo->getAllInstitutions();
      $att_repo= new AttentionRepository();
      $motivos= $att_repo->getMotivosDeConsulta();
      $tratamientos= $att_repo->getTratamientos();
      $acomps= $att_repo->getAcompanamientos();
      if ($fecha < date('Y-m-d')){
        $err.= 'error con la fecha ingresada: la fecha no puede ser anterior a hoy; ';
      }
      if (!($this->isValidId($derivacion, $instituciones))){
        $err.= 'error con derivación: la opción ingresada no es válida; ';
      }
      if (!($this->isValidId($motivo, $motivos))){
        $err.= 'error con motivo: la opción ingresada no es válida; ';
      }
      if (isset($trat) && !empty($trat)){
        if (!($this->isValidId($trat, $tratamientos))){
          $err.= 'error con tratamiento: la opción ingresada no es válida; ';
        }
      }
      if (isset($acomp) && !empty($acomp)){
        if (!($this->isValidId($acomp, $acomps))){
          $err.= 'error con acompañamiento: la opción ingresada no es válida; ';
        }
      }
      // if ( ($internacion != '0') || ($internacion != '1') ){
      //   $err.= 'error con internacion: la opción ingresada no es válida; ';
      // }
    }else {
      $err.= 'Se produjo un error: faltó completar alguno/s de los datos.';
    }
    return $err;
  }

  function isValidId($post_id, $table){
    foreach ($table as $key => $value) {
      if( $post_id == $value['id'] ){
        return True;
      }
    }
    return False;//no encontró el id en la tabla
  }

  function addAttention(){
    if(!is_null(AppController::getInstance()->getUser())){
      if(AppController::getInstance()->checkPermissions($_GET['action'])){
        if($this->checkToken('atencion_new')){
          $this->prepareData(array('motivo', 'derivacion', 'articulacion', 'fecha_consulta', 'internacion', 'diag', 'obs', 'trat', 'acomp'));
          $err= $this->isValidForm($_POST['fecha_consulta'], $_POST['derivacion'], $_POST['motivo'], $_POST['articulacion'], $_POST['internacion'], $_POST['diag'], $_POST['obs'], $_POST['trat'], $_POST['acomp']);
          $id_paciente= $_POST['id_paciente'];
          if(empty($err)){
            $repo= new AttentionRepository();
            $repo->newAttention($id_paciente, $_POST['derivacion'], $_POST['motivo'], $_POST['articulacion'], $_POST['fecha_consulta'], $_POST['internacion'], $_POST['diag'], $_POST['obs'], $_POST['trat'], $_POST['acomp']);
            $this->viewAttentionsList('success', 'Atención agregada');
          }else {
            $this->viewAttentionsList('error', $err);
          }
        }else {
          $this->viewAttentionsList('error', 'Error con la validación del Token');
        }
      }else {
        $this->viewAttentionsList('error', 'Se produjo un error: no tienes permiso para realizar esa acción');
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
        $porcentaje= ($row->cant * 100 / $row->total_atenciones);
        array_push($dataPoints, array('y'=> $porcentaje, 'label'=> $row->nombre, 'cant'=> $row->cant));
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
      $porcentaje= ($row->cant * 100 / $row->total_atenciones);
      $loc_nombre= $row->id == 0 ? 'Sin localidad asignada' : $loc_array[$row->id];
      array_push($dataPoints, array('y'=> $porcentaje, 'label'=> $loc_nombre, 'cant'=> $row->cant));
    }
    echo json_encode($dataPoints, JSON_NUMERIC_CHECK);
  }


}

?>
