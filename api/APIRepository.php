<?php


class APIRepository{

  private $username = "grupo23";
  private $password = "ZjJjODE4MTY5M2U3";
  private $host ="localhost";
  private $db = "grupo23";
  protected $conn;


  public function getConnection(){
      return new PDO("mysql:dbname=" . $this->db . ";host=" . $this->host . ";charset=utf8", $this->username, $this->password);
  }

  function getAllInstitutions(){
    $query=$this->getConnection()->prepare("SELECT * FROM institucion");
    $query->execute();
    return $query->fetchall(PDO::FETCH_ASSOC);
  }

  function getInstitutionByID($id){
    $query=$this->getConnection()->prepare("SELECT * FROM institucion WHERE id=:id");
    $query->bindParam(':id',$id);
    $query->execute();
    return $query->fetchall(PDO::FETCH_ASSOC);
  }

  function getInstitutionByRSID($id){
    $query=$this->getConnection()->prepare("SELECT * FROM institucion WHERE region_sanitaria_id=:id");
    $query->bindParam(':id',$id);
    $query->execute();
    return $query->fetchall(PDO::FETCH_ASSOC);
  }
}
