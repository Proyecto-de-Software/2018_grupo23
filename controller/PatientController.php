<?php

require_once('model/PatientRepository.php');

/**
 * Controlador del usuario
 */
class PatientController extends MainController{
  protected static $instance;
  protected static $twig;

  function viewAddPatient(){
    $partidos=json_decode(file_get_contents('https://api-referencias.proyecto2018.linti.unlp.edu.ar/partido'));
    $regiones_s=json_decode(file_get_contents('https://api-referencias.proyecto2018.linti.unlp.edu.ar/region-sanitaria'));
    $tipo_doc=json_decode(file_get_contents('https://api-referencias.proyecto2018.linti.unlp.edu.ar/tipo-documento'));
    $obras_sociales=json_decode(file_get_contents('https://api-referencias.proyecto2018.linti.unlp.edu.ar/obra-social'));
    $param = array('partidos' =>$partidos, 'regiones_s' =>$regiones_s, 'tipo_doc' =>$tipo_doc, 'obras_sociales' => $obras_sociales );
      $this::$twig->show('add_patient.html',$param);
  }

  function addPatient(){
    if((isset($_POST["apellido"])) && (isset($_POST["nombre"])) && (isset($_POST["dob"])) && (isset($_POST["dobplace"]))
    && (isset($_POST["partido"]))&& (isset($_POST["regions"])) && (isset($_POST["localidad"])) && (isset($_POST["domicilio"]))
    && (isset($_POST["genero"])) && (isset($_POST["doccheck"])) && (isset($_POST["typedoc"])) && (isset($_POST["numdoc"]))
    && (isset($_POST["numhc"]))&& (isset($_POST["numcarpeta"])) && (isset($_POST["telefono"])) && (isset($_POST["obra_social"]))){
      if((!empty($_POST["apellido"])) && (!empty($_POST["nombre"])) && (!empty($_POST["dob"])) && (!empty($_POST["dobplace"]))
      && (!empty($_POST["partido"]))&& (!empty($_POST["regions"])) && (!empty($_POST["localidad"])) && (!empty($_POST["domicilio"]))
      && (!empty($_POST["genero"])) && (!empty($_POST["doccheck"])) && (!empty($_POST["typedoc"])) && (!empty($_POST["numdoc"]))
      && (!empty($_POST["numhc"]))&& (!empty($_POST["numcarpeta"])) && (!empty($_POST["telefono"])) && (!empty($_POST["obra_social"]))){
        $query=new PatientRepository();
        $query->newPatient($_POST["apellido"],$_POST["nombre"],$_POST["dob"],$_POST["dobplace"],$_POST["regions"]
        ,$_POST["localidad"],$_POST["domicilio"],$_POST["genero"],$_POST["doccheck"],$_POST["typedoc"],$_POST["numdoc"]
        ,$_POST["numhc"],$_POST["numcarpeta"],$_POST["telefono"],$_POST["obra_social"]);
        $this->redirectHome();
      }
      //error del segundo if (empty)
    }
    //error del primer if (unset)
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
}

?>
