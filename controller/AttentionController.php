<?php

require_once('model/AttentionRepository.php');
require_once('model/APIRepository.php');

class AttentionController extends MainController{

  protected static $instance;
  protected static $twig;

  function viewAttentionsList($state=NULL, $msg=""){
    if(AppController::getInstance()->checkPermissions('atencion_index')){
      if(isset($_POST['id_paciente']) && !empty($_POST['id_paciente'])){
        $repo= new AttentionRepository();
        $tratamientos= $repo->getTratamientos();
        $acomps= $repo->getAcompanamientos();
        $motivos= $repo->getMotivosDeConsulta();
        $instituciones= json_decode(file_get_contents('https://grupo23.proyecto2018.linti.unlp.edu.ar/api.php/instituciones'));
        $atenciones= $repo->getAtencionesFromPaciente($_POST['id_paciente']);
        $params = array('atenciones' => $atenciones,'permisos'=>$_SESSION['permissions'], 'id_paciente'=>$_POST['id_paciente'], 'fecha_hoy'=>date('Y-m-d'), 'instituciones'=> $instituciones, 'tratamientos'=>$tratamientos, 'acomps'=>$acomps, 'motivos'=>$motivos);
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
  function isValidForm($derivacion, $motivo, $art, $internacion, $diag, $obs, $trat, $acomp){
      $err='';
      $instituciones= json_decode(file_get_contents('https://grupo23.proyecto2018.linti.unlp.edu.ar/api.php/instituciones'), True);
      $att_repo= new AttentionRepository();
      $motivos= $att_repo->getMotivosDeConsulta();
      $tratamientos= $att_repo->getTratamientos();
      $acomps= $att_repo->getAcompanamientos();
      // if ($fecha < date('Y-m-d')){
      //   $err.= 'error con la fecha ingresada: la fecha no puede ser anterior a hoy; ';
      // }
      if(!empty($derivacion)){
        if (!$this->isValidId($derivacion, $instituciones)){
          $err.= 'error con derivación: la opción ingresada no es válida; ';
        }
      }
      if (!$this->isValidId($motivo, $motivos)){
        $err.= 'error con motivo: la opción ingresada no es válida; ';
      }
      if (isset($trat) && !empty($trat)){
        if (!$this->isValidId($trat, $tratamientos)){
          $err.= 'error con tratamiento: la opción ingresada no es válida; ';
        }
      }
      if (isset($acomp) && !empty($acomp)){
        if (!$this->isValidId($acomp, $acomps)){
          $err.= 'error con acompañamiento: la opción ingresada no es válida; ';
        }
      }
      if ( ($internacion < 0) || ($internacion > 1) ){
        $err.= 'error con internacion: la opción ingresada no es válida; ';
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
      if(AppController::getInstance()->checkPermissions('atencion_new')){
        if($this->checkToken('atencion_new')){
          $this->prepareData(array('motivo', 'derivacion', 'articulacion', 'internacion', 'diag', 'obs', 'trat', 'acomp'));
          if($this->postElementsCheck(array('motivo', 'internacion', 'diag'))){
            $err= $this->isValidForm($_POST['derivacion'], $_POST['motivo'], $_POST['articulacion'], $_POST['internacion'], $_POST['diag'], $_POST['obs'], $_POST['trat'], $_POST['acomp']);
            $id_paciente= $_POST['id_paciente'];
            if(empty($err)){
              $repo= new AttentionRepository();
              $repo->newAttention($id_paciente, $_POST['derivacion'], $_POST['motivo'], $_POST['articulacion'], Date('Y-m-d'), $_POST['internacion'], $_POST['diag'], $_POST['obs'], $_POST['trat'], $_POST['acomp']);
              $this->viewAttentionsList('success', 'Atención agregada');
            }else {
              $this->viewAttentionsList('error', $err);
            }
          }else{$this->viewAttentionsList('error', 'Se produjo un error: faltó completar alguno/s de los datos.');}
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

  
  function editAttention(){
    if(!is_null(AppController::getInstance()->getUser())){
      if(AppController::getInstance()->checkPermissions('atencion_update')){
        if($this->checkToken('atencion_update')){
          $this->prepareData(array('motivo', 'derivacion', 'articulacion', 'internacion', 'diag', 'obs', 'trat', 'acomp'));
          if($this->postElementsCheck(array('motivo', 'internacion', 'diag'))){
            $err= $this->isValidForm($_POST['derivacion'], $_POST['motivo'], $_POST['articulacion'], $_POST['internacion'], $_POST['diag'], $_POST['obs'], $_POST['trat'], $_POST['acomp']);
            $id= $_POST['id_at'];
            if(empty($err)){
              $repo= new AttentionRepository();
              $repo->updateAttention($id, $_POST['derivacion'], $_POST['motivo'], $_POST['articulacion'], Date('Y-m-d'), $_POST['internacion'], $_POST['diag'], $_POST['obs'], $_POST['trat'], $_POST['acomp']);
              $this->viewAttentionsList('success', 'Atención actualizada');
            }else {
              $this->viewAttentionsList('error', $err);
            }
          }else{$this->viewAttentionsList('error', 'Se produjo un error: faltó completar alguno/s de los datos.');}
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

  function getAttentionJSON(){
    if(AppController::getInstance()->getUser()){
      if( AppController::getInstance()->checkPermissions('atencion_show') || AppController::getInstance()->checkPermissions('atencion_update')){
        if(isset($_POST['id']) && !empty($_POST['id'])){
          $query = new AttentionRepository();
          $atention = $query->getAtencionFromId($_POST['id']);
          echo json_encode($atention);
        }else {$this->redirectHome();}
      }else {$this->redirectHome();}
    }else {$this->redirectHome();}

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
