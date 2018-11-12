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


}
