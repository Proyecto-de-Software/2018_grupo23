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
    $genero,$doccheck,$tipo_documento,$numero_documento,$numero_carpeta,$telefono,$obra_social,$partido){
      $query = $this->conn->prepare("INSERT INTO paciente (apellido,nombre,fecha_nac,lugar_nac,localidad_id,region_sanitaria_id,
      domicilio,genero_id,tiene_documento,tipo_doc_id,numero,tel,nro_carpeta,obra_social_id,partido_id)
      VALUES(:apellido,:nombre,:dob,:dobplace,:localidad,:region_sanitaria,:domicilio,:genero,:doccheck,
      :tipo_documento,:numero_documento,:telefono,:numero_carpeta,:obra_social,:partido)");
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
      if($numero_carpeta=''){
        $query->bindParam(":numero_carpeta",null);
      }
      else {
        $query->bindParam(":numero_carpeta",$numero_carpeta);
      }

      $query->bindParam(":telefono",$telefono);
      $query->bindParam(":obra_social",$obra_social);
      $query->bindParam(":partido",$partido);
      $query->execute();
      }
    /* End of create functions */

    /* READ functions */
    function getPatient($id){
      $query= $this->conn->prepare("SELECT * FROM paciente WHERE paciente.id= :id");
      $query->bindParam(":id",$id);
      $query->execute();
      return $query->fetchall();
    }

    function getAllPatients(){
      $query=$this->conn->prepare("SELECT * FROM paciente");
      $query->execute();
      return $query->fetchall();
    }

    function getAllGenders(){
      $query=$this->conn->prepare("SELECT * FROM genero");
      $query->execute();
      return $query->fetchall();
    }

    function getGenero($id){
      $query=$this->conn->prepare("SELECT * FROM genero WHERE :id=id");
      $query->bindParam(":id",$id);
      $query->execute();
      return $query->fetchall();
    }

    function isDocAvailable($type,$num){
      $query=$this->conn->prepare("SELECT * FROM paciente WHERE paciente.tipo_doc_id= :type AND paciente.numero= :num");
      $query->bindParam(":type",$type);
      $query->bindParam(":num",$num);
      return !empty($query->execute());
    }
    /* End of read  functions */

    /* Update functions */

    function updatePatient($id,$apellido,$nombre,$dob,$dobplace,$region_sanitaria,$localidad,$domicilio,
    $genero,$doccheck,$tipo_documento,$numero_documento,$numero_carpeta,$telefono,$obra_social,$partido){
      $query = $this->conn->prepare("UPDATE paciente
                                      SET apellido=:apellido,
                                          nombre=:nombre,
                                          fecha_nac=:dob,
                                          lugar_nac=:dobplace,
                                          localidad_id=:localidad,
                                          region_sanitaria_id=:region_sanitaria,
                                          domicilio=:domicilio,
                                          genero_id=:genero,
                                          tiene_documento=:doccheck,
                                          tipo_doc_id=:tipo_documento,
                                          numero=:numero_documento,
                                          tel=:telefono,
                                          nro_carpeta=:numero_carpeta,
                                          obra_social_id=:obra_social,
                                          partido_id=:partido
                                      WHERE id=:id");
  $query->bindParam(":apellido",$apellido);
  $query->bindParam(":nombre",$nombre);
  $query->bindParam(":dob",$dob);
  $query->bindParam(":dobplace",$dobplace);
  $query->bindParam(":localidad",$localidad);
  $query->bindParam(":region_sanitaria",$region_sanitaria);
  $query->bindParam(":domicilio",$domicilio);
  $query->bindParam(":genero",$genero);
  $query->bindParam(":doccheck",$doccheck);
  $query->bindParam(":tipo_documento",$tipo_documento);
  $query->bindParam(":numero_documento",$numero_documento);
  $query->bindParam(":telefono",$telefono);
  $query->bindParam(":numero_carpeta",$numero_carpeta);
  $query->bindParam(":obra_social",$obra_social);
  $query->bindParam(":partido",$partido);
  $query->bindParam(":id",$id);
  $query->execute();
    }

    /* End of update functions */

    /* Delete functions */

    function removePatient($id){
      $query=$this->conn->prepare("DELETE FROM paciente WHERE id=:id");
      $query->bindParam(":id",$id);
      $query->execute();
    }
    /* End of delete functions */
  }


?>
