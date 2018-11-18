<?php
require_once('core/Connection.php');

class APIRepository extends Connection{

  function __construct(){
    parent::__construct();
  }

  function getAllInstitutions(){
    $query= $this->conn->prepare("SELECT * FROM institucion");
    $query->execute();
    return $query->fetchall(PDO::FETCH_ASSOC);
  }

  function getInstitutionByID($id){
    $query= $this->conn->prepare("SELECT * FROM institucion WHERE id=:id");
    $query->bindParam(':id',$id);
    $query->execute();
    return $query->fetchall(PDO::FETCH_ASSOC);
  }

  function getInstitutionByRSID($id){
    $query= $this->conn->prepare("SELECT * FROM institucion WHERE region_sanitaria_id=:id");
    $query->bindParam(':id',$id);
    $query->execute();
    return $query->fetchall(PDO::FETCH_ASSOC);
  }
}
