<?php

require_once('model/PatientRepository.php');

/**
 * Controlador del usuario
 */
class PatientController extends MainController{
  protected static $instance;
  protected static $twig;

  function viewAddPatient(){
     //if((!is_null(AppController::getInstance()->getUser())) && chequeodepermiso)
    $tipo_doc=json_decode(file_get_contents('https://api-referencias.proyecto2018.linti.unlp.edu.ar/tipo-documento'));
    $obras_sociales=json_decode(file_get_contents('https://api-referencias.proyecto2018.linti.unlp.edu.ar/obra-social'));
    $param = array('tipo_doc' =>$tipo_doc, 'obras_sociales' => $obras_sociales );
      $this::$twig->show('add_patient.html',$param);
  }

  function addPatient(){
    if($this->postElementsCheck( array('apellido','nombre','dob','domicilio','genero','doccheck','typedoc','numdoc'))){
        $query=new PatientRepository();
        $query->newPatient($_POST["apellido"],$_POST["nombre"],$_POST["dob"],$_POST["dobplace"],$_POST["regions"]
        ,$_POST["localidad"],$_POST["domicilio"],$_POST["genero"],$_POST["doccheck"],$_POST["typedoc"],$_POST["numdoc"]
        ,$_POST["numcarpeta"],$_POST["telefono"],$_POST["obra_social"]);
        $this->viewPatientList();
      }
  }

  function addNN(){
    $query=new PatientRepository();
    $query->newPatient('NN','NN',2018-12-31,'NN',1,1,'',1,0,1,1111111,0000,00000000,1);
    $this->viewPatientList();
  }

  function viewPatient(){
    $query=new PatientRepository();
    $patient=$query->getPatient(14); //cambiar por POST_ID
    if(!empty($patient)){
      $p_localidad=json_decode(file_get_contents('https://api-referencias.proyecto2018.linti.unlp.edu.ar/localidad/'.$patient[0]["localidad_id"]));
      $p_region_s=json_decode(file_get_contents('https://api-referencias.proyecto2018.linti.unlp.edu.ar/region-sanitaria/'.$patient[0]["region_sanitaria_id"]));
      $p_tipo_doc=json_decode(file_get_contents('https://api-referencias.proyecto2018.linti.unlp.edu.ar/tipo-documento/'.$patient[0]["tipo_doc_id"]));
      $p_obra_social=json_decode(file_get_contents('https://api-referencias.proyecto2018.linti.unlp.edu.ar/obra-social/'.$patient[0]["obra_social_id"]));
      if((!empty($p_localidad)) && (!empty($p_region_s)) && (!empty($p_tipo_doc)) && (!empty($p_obra_social))){
        //llamar a twig y pasarle todas las variables para mostrar datos del usuario
      }
    }
    else{
      //tirar error que no encontró el paciente
  }
}
  function viewPatientList(){
    $query=new PatientRepository();
    $patient_list=$query->getAllPatients();
    $tipo_doc=json_decode(file_get_contents('https://api-referencias.proyecto2018.linti.unlp.edu.ar/tipo-documento'));
    $obras_sociales=json_decode(file_get_contents('https://api-referencias.proyecto2018.linti.unlp.edu.ar/obra-social'));
    $param = array('tipo_doc' =>$tipo_doc, 'obras_sociales' => $obras_sociales,'patient_list'=>$patient_list );
    $this::$twig->show('list_patients.html',$param);
}

}

?>
