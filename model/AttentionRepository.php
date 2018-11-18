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

  /*reportes*/
  public function getAtencionesPorMotivo(){
    $query = $this->conn->prepare("SELECT mc.nombre as nombre, (SELECT COUNT(consulta.motivo_id) * 100 / COUNT(c.id) FROM consulta AS c) AS porcentaje, COUNT(consulta.motivo_id) as cant
                                    FROM consulta INNER JOIN motivo_consulta mc ON consulta.motivo_id=mc.id
                                    GROUP BY consulta.motivo_id");
    $query->execute();
    return $query->fetchAll(PDO::FETCH_OBJ);
  }

  public function getAtencionesPorGenero(){
    $query = $this->conn->prepare("SELECT g.nombre as nombre, (SELECT COUNT(paciente.genero_id) * 100 / COUNT(c.id) FROM paciente INNER JOIN consulta c ON c.paciente_id=paciente.id) AS porcentaje, COUNT(p.genero_id) as cant
                                    FROM consulta INNER JOIN paciente p ON consulta.paciente_id=p.id INNER JOIN genero g ON p.genero_id=g.id
                                    GROUP BY p.genero_id");
    $query->execute();
    return $query->fetchAll(PDO::FETCH_OBJ);
  }

  public function getAtencionesPorLocalidad(){//localidad del paciente, no de la institución
    $query= $this->conn->prepare("SELECT p.localidad_id as id, (SELECT COUNT(paciente.localidad_id) * 100 / COUNT(c.id) FROM paciente INNER JOIN consulta c ON c.paciente_id=paciente.id) AS porcentaje, COUNT(p.localidad_id) as cant
                                    FROM consulta INNER JOIN paciente p ON p.id=consulta.paciente_id
                                    GROUP BY p.localidad_id");
    $query->execute();
    return $query->fetchAll(PDO::FETCH_OBJ);
  }

  function newAttention($id_p, $derivacion, $motivo, $art, $fecha, $internacion, $diag, $obs, $trat, $acomp){
    $query= $this->conn->prepare("INSERT INTO consulta(paciente_id, fecha, motivo_id, derivacion_id, articulacion_con_instituciones,
                                  internacion, diagnostico, observaciones, tratamiento_farmacologico_id, acompanamiento_id)
                                  VALUES(:paciente_id, :fecha, :motivo_id, :derivacion_id, :art_con_inst_id, :inter, :diag, :obsr, :trat_id, :acomp_id)");
    $query->bindParam(":paciente_id", $id_p);
    $query->bindParam(":fecha", $fecha);
    $query->bindParam(":motivo_id", $motivo);
    $query->bindParam(":derivacion_id", $derivacion);
    $query->bindParam(":art_con_inst_id", $art);
    $query->bindParam(":inter", $internacion);
    $query->bindParam(":diag", $diag);
    $query->bindParam(":obsr", $obs);
    $query->bindParam(":trat_id", $trat);
    $query->bindParam(":acomp_id", $acomp);
    $query->execute();
  }

}
