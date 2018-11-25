<?php

require_once('model/PatientRepository.php');

/**
 * Controlador del usuario
 */
class PatientController extends MainController{
  protected static $instance;
  protected static $twig;

  function addPatient(){
    if(AppController::getInstance()->checkPermissions($_GET['action'])){
      if(!isset($_POST["addNN"])){
        $this->prepareData(array('apellido','nombre','dobplace','domicilio'));
        if($this->postElementsCheck( array('apellido','nombre','dob','domicilio','genero','typedoc','numdoc'))){
          if($this->isValidId("https://api-referencias.proyecto2018.linti.unlp.edu.ar/partido",$_POST["partido"]) &&
            $this->isValidId("https://api-referencias.proyecto2018.linti.unlp.edu.ar/region-sanitaria",$_POST["regions"]) &&
            $this->isValidId("https://api-referencias.proyecto2018.linti.unlp.edu.ar/localidad",$_POST["localidad"]) &&
            $this->isValidId("https://api-referencias.proyecto2018.linti.unlp.edu.ar/obra-social",$_POST["obra_social"]) &&
            $this->isValidId("https://api-referencias.proyecto2018.linti.unlp.edu.ar/tipo-documento",$_POST["typedoc"]) &&
            $this->checkDate($_POST["dob"]) &&
            is_numeric($_POST['numdoc'])){
              if($this->checkDoc($_POST["typedoc"],$_POST["numdoc"])){
                if($this->checkToken('paciente_new')){
                  $query=new PatientRepository();
                  $query->newPatient($_POST["apellido"],$_POST["nombre"],$_POST["dob"],$_POST["dobplace"],$_POST["regions"]
                  ,$_POST["localidad"],$_POST["domicilio"],$_POST["genero"],$_POST["doccheck"],$_POST["typedoc"],$_POST["numdoc"]
                  ,$_POST["numcarpeta"],$_POST["telefono"],$_POST["obra_social"],$_POST["partido"]);
                  $this->viewPatientList('success','El paciente ha sido agregado exitosamente.');
                }else {
                  $this->viewPatientList('error','No deberías estar haciendo esto.');
                }
              }else {
                $this->viewPatientList('error','El documento ya está en uso.');
              }
         }else {
           $this->viewPatientList('error','Alguno de los campos no fue válido.');
         }
       }else {
          $this->viewPatientList('error','Alguno de los campos no fue válido.');
       }
      }else {
        $this->addNN();
      }
    }else{
      $this->viewPatientList('error','No tienes permisos para hacer esto.');
    }
  }

  function isValidId($url,$id){
    if(!empty($id)){
      $data=json_decode(@file_get_contents($url),true);
      for($a=0;$a<count($data);$a++){
      if(in_array($id,$data[$a])){
        return True;
      }
    }
    return False;
    }else {
      return True; //si el id esta vacio paso true ya que no puedo chequear que un campo nulo no pertenezca al conjunto de la API
    }
  }

  function checkDate($date){
    return $date<date('Y-m-d');
  }

  function addNN(){
    if($this->checkToken('paciente_new')){
      $query=new PatientRepository();
      $user=$query->newPatient('NN','NN',2018-12-31,'NN',0,0,'',1,0,1,1111111,0,0,0,0);
      $this->viewPatientList('success','El paciente ha sido agregado exitosamente con numero de historia clinica: '.$user);
    }
    else {
      $this->viewPatientList('error','No deberías estar haciendo esto.');
    }
  }

  function checkDoc($type,$num){
    $query=new PatientRepository();
    return $query->isDocAvailable($type,$num);
  }

  function viewPatient(){
    if(AppController::getInstance()->getUser()){
      $app_contr= AppController::getInstance();
      if( $app_contr->checkPermissions('paciente_show') || $app_contr->checkPermissions('paciente_update') ){
        $query=new PatientRepository();
        $patient=$query->getPatient($_POST["id"]);
        if(!empty($patient)){
          if($patient[0]["partido_id"]!=0){
            $p_partido=json_decode(@file_get_contents('https://api-referencias.proyecto2018.linti.unlp.edu.ar/partido/'.$patient[0]["partido_id"]),true);
          }else{
            $p_partido=array("id"=>0,"nombre"=>"NN");
          }
          if($patient[0]["localidad_id"]!=0){
            $p_localidad=json_decode(@file_get_contents('https://api-referencias.proyecto2018.linti.unlp.edu.ar/localidad/'.$patient[0]["localidad_id"]),true);
          }else {
            $p_localidad=array("id"=>0,"nombre"=>"NN");
          }
          if($patient[0]["region_sanitaria_id"]!=0){
            $p_region_s=json_decode(@file_get_contents('https://api-referencias.proyecto2018.linti.unlp.edu.ar/region-sanitaria/'.$patient[0]["region_sanitaria_id"]),true);
          }else{
            $p_region_s=array("id"=>0,"nombre"=>"NN");
          }
          $p_tipo_doc=json_decode(@file_get_contents('https://api-referencias.proyecto2018.linti.unlp.edu.ar/tipo-documento/'.$patient[0]["tipo_doc_id"]),true);
          if($patient[0]["obra_social_id"]!=0){
            $p_obra_social=json_decode(@file_get_contents('https://api-referencias.proyecto2018.linti.unlp.edu.ar/obra-social/'.$patient[0]["obra_social_id"]),true);
          }else {
            $p_obra_social=array("id"=>0,"nombre"=>"NN");
          }
          if((!empty($p_localidad)) && (!empty($p_region_s)) && (!empty($p_tipo_doc)) && (!empty($p_obra_social))){
            $patient[0]["localidad_id"]=$p_localidad;
            $patient[0]["region_sanitaria_id"]=$p_region_s;
            $patient[0]["tipo_doc_id"]=$p_tipo_doc;
            $patient[0]["obra_social_id"]=$p_obra_social;
            $patient[0]["partido_id"]=$p_partido;
            $query=new PatientRepository();
            $patient[0]["genero_id"]=$query->getGenero($patient[0]["genero_id"]);
            echo(json_encode($patient));
          }
        }else {
          $this->viewPatientList('error','No se encontró el paciente');
        }
      }else {
        $this->redirectHome();
      }
    }else {
      $this->redirectHome();
    }
  }

  function pacienteIndex(){
    if(AppController::getInstance()->checkPermissions($_GET['action'])){
      $this->viewPatientList();
    }else {
      $this->redirectHome();
    }
  }

  function viewPatientList($state="",$message=""){
    if(AppController::getInstance()->checkPermissions('paciente_index')){
      $query=new PatientRepository();
      $patient_list=$query->getAllPatients();
      $gender_list=$query->getAllGenders();
      $tipo_doc=json_decode(@file_get_contents('https://api-referencias.proyecto2018.linti.unlp.edu.ar/tipo-documento'),true);
      $obras_sociales=json_decode(@file_get_contents('https://api-referencias.proyecto2018.linti.unlp.edu.ar/obra-social'),true);
      $param = array('tipo_doc' =>$tipo_doc, 'obras_sociales' => $obras_sociales,'patient_list'=>$patient_list,'genders'=>$gender_list, 'permisos' =>$_SESSION['permissions']);
      if($state=='success'){
        $param['success']=$message;
      }elseif ($state=='error') {
        $param['error']=$message;
      }
      $this::$twig->show('list_patients.html',$param);
    }else {
      $this->redirectHome();
    }
  }

  function deletePatient(){
    if(AppController::getInstance()->checkPermissions($_GET['action'])){
      if($this->checkToken('paciente_destroy')){
        $query=new PatientRepository();
        $query->removePatient($_POST['id_paciente']);
        $this->viewPatientList('success','El paciente ha sido eliminado exitosamente');
      }else {
        $this->viewPatientList('error','No deberías estar haciendo esto.');
      }
    }else {
      $this->viewPatientList('error','No tienes permisos para eliminar pacientes.');
    }
  }

  function updatePatient(){
    if(AppController::getInstance()->checkPermissions($_GET['action'])){
      $this->prepareData(array('apellido','nombre','dobplace','domicilio'));
      if($this->postElementsCheck( array('apellido','nombre','dob','domicilio','genero','typedoc','numdoc'))){
          if($this->isValidId("https://api-referencias.proyecto2018.linti.unlp.edu.ar/partido",$_POST["partido"]) &&
            $this->isValidId("https://api-referencias.proyecto2018.linti.unlp.edu.ar/region-sanitaria",$_POST["regions"]) &&
            $this->isValidId("https://api-referencias.proyecto2018.linti.unlp.edu.ar/localidad",$_POST["localidad"]) &&
            $this->isValidId("https://api-referencias.proyecto2018.linti.unlp.edu.ar/obra-social",$_POST["obra_social"]) &&
            $this->isValidId("https://api-referencias.proyecto2018.linti.unlp.edu.ar/tipo-documento",$_POST["typedoc"]) &&
            $this->checkDate($_POST["dob"]) &&
            is_numeric($_POST['numdoc'])){
              if($this->checkDocbyID($_POST["edit_id"],$_POST["typedoc"],$_POST["numdoc"])){
                if($this->checkToken('paciente_new')){
                  $query=new PatientRepository();
                  $query->updatePatient($_POST["edit_id"],$_POST["apellido"],$_POST["nombre"],$_POST["dob"],$_POST["dobplace"],$_POST["regions"]
                  ,$_POST["localidad"],$_POST["domicilio"],$_POST["genero"],$_POST["doccheck"],$_POST["typedoc"],$_POST["numdoc"]
                  ,$_POST["numcarpeta"],$_POST["telefono"],$_POST["obra_social"],$_POST["partido"]);
                  $this->viewPatientList();
                }else {
                    $this->viewPatientList('error','No tienes permisos para hacer esto');
                }
          }else {
            $this->viewPatientList('error','El documento ya está en uso.');
          }
        }else {
            $this->viewPatientList('error','Alguno de los campos no fue válido.');
        }
      }else {
        $this->viewPatientList('error','Alguno de los campos no fue válido.');
      }
    }else {
    $this->viewPatientList('error','No tienes permisos para editar pacientes.');
    }
  }

  function checkDocbyID($id,$type,$num){
    $query=new PatientRepository();
    return $query->isDocAvailableWithID($id,$type,$num);
  }

}


?>
