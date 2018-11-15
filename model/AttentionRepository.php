<?php

require_once('core/Connection.php');
  /**
   * Repositorio de atenciones
  */
class AttentionRepository extends Connection{

  function __construct(){
    parent::__construct();
  }

  function getTratamientos(){
    $query= $this->conn->prepare("SELECT * FROM tratamiento_farmacologico");
    $query->execute();
    return $query->fetchall();
  }

  function getMotivosDeConsulta(){
    $query= $this->conn->prepare("SELECT * FROM motivo_consulta");
    $query->execute();
    return $query->fetchall();
  }

  function getAcompanamientos(){
    $query= $this->conn->prepare("SELECT * FROM acompanamiento");
    $query->execute();
    return $query->fetchall();
  }

  //después hay que agregar como parámetro la institución(APi)
  function newAttention($id_p, $motivo, $art, $fecha, $internacion, $diag, $obs, $trat, $acomp){
    $query= $this->conn->prepare("INSERT INTO consulta(paciente_id, fecha, motivo_id, derivacion_id, articulacion_con_instituciones,
                                  internacion, diagnostico, observaciones, tratamiento_farmacologico_id, acompanamiento_id)
                                  VALUES(:paciente_id, :fecha, :motivo_id, NULL, :art_con_inst_id, :inter, :diag, :obsr, :trat_id, :acomp_id)");
    $query->bindParam(":paciente_id", $id_p);
    $query->bindParam(":fecha", $fecha);
    $query->bindParam(":motivo_id", $motivo);
    $query->bindParam(":art_con_inst_id", $art);
    $query->bindParam(":inter", $internacion);
    $query->bindParam(":diag", $diag);
    $query->bindParam(":obsr", $obs);
    $query->bindParam(":trat_id", $trat);
    $query->bindParam(":acomp_id", $acomp);
    $query->execute();
  }


}
