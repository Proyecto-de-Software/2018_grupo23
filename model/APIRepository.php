<?php

require_once('core/Connection.php');
  /**
   * Repositorio de atenciones
  */
class APIRepository extends Connection{

  function __construct(){
    parent::__construct();
  }

  function getAllInstitutions(){
    $query=$this->conn->prepare("SELECT * FROM institucion");
    $query->execute();
    return $query->fetchall();
  }
}
