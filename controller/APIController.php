<?php

require_once('model/APIRepository.php');

/**
 * Controlador de la API
 */
class APIController{
  protected static $instance;

  private function __construct(){}

  final protected function __clone(){}

  public static function getInstance(){
    if (!static::$instance instanceof static) {
      static::$instance = new static();
    }
    return static::$instance;
  }

 function list_institutions(){
    $db=new APIRepository;
    echo json_encode($db->getAllInstitutions());
  }

  function list_institution($id){
    $db=new APIRepository;
    echo json_encode($db->getInstitutionByID($id));
  }

  function list_institution_by_RS($id){
    $db=new APIRepository;
    echo json_encode($db->getInstitutionByRSID($id));
  }



}
