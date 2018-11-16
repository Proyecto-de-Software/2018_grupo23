<?php

require_once('model/APIRepository.php');

/**
 * Controlador de la API
 */
class APIController extends MainController{
  protected static $instance;
  protected static $twig;

  function list_instituciones(){
    $db=new APIRepository;
    echo json_encode($db->getAllInstitutions());
  }

}
