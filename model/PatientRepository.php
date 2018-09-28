<?php
require_once('core/Connection.php');
  /**
   * Repositorio de usuario
   */
  class PatientRepository extends Connection{

    function __construct(){
      parent::__construct();
    }

    /* Create functions */
    function newPatient($apellido,$nombre,$dob,$dobplace,$region_sanitaria,$localidad,$domicilio,
    $genero,$doccheck,$tipo_documento,$numero_documento,$numero_historia_clinica,$numero_carpeta,$telefono,$obra_social){
      $query = $this->conn->prepare("INSERT INTO paciente (apellido,nombre,fecha_nac,lugar_nac,localidad_id,region_sanitaria_id,
      domicilio,genero_id,tiene_documento,tipo_doc_id,numero,tel,nro_historia_clinica,nro_carpeta,obra_social_id)
      VALUES(:apellido,:nombre,:dob,:dobplace,:region_sanitaria,:localidad,:domicilio,:genero,:doccheck,
      :tipo_documento,:numero_documento,:numero_historia_clinica,:numero_carpeta,:telefono,:obra_social)");
      $query->bindParam(":apellido",$apellido);
      $query->bindParam(":nombre",$nombre);
      $query->bindParam(":dob",$dob);
      $query->bindParam(":dobplace",$dobplace);
      $query->bindParam(":region_sanitaria",$region_sanitaria);
      $query->bindParam(":localidad",$localidad);
      $query->bindParam(":domicilio",$domicilio);
      $query->bindParam(":genero",$genero);
      $query->bindParam(":doccheck",$doccheck);
      $query->bindParam(":tipo_documento",$tipo_documento);
      $query->bindParam(":numero_documento",$numero_documento);
      $query->bindParam(":numero_historia_clinica",$numero_historia_clinica);
      $query->bindParam(":numero_carpeta",$numero_carpeta);
      $query->bindParam(":telefono",$telefono);
      $query->bindParam(":obra_social",$obra_social);
      $query->execute();
      $query->debugDumpParams(); // para debugear las consultas!
      die();

    }
    /* End of create functions */

    /* READ functions */
    /* End of read  functions */

    /* Update functions */
    /* End of update functions */

    /* Delete functions */
    /* End of delete functions */
  }


?>
