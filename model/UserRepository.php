<?php
require_once('core/Connection.php');
  /**
   * Repositorio de usuario
   */
  class UserRepository extends Connection{

    function __construct(){
      parent::__construct();
    }

    /* Create functions */
    /* End of create functions */

    /* READ functions */
    public function loginUsuario($user_name,$pass){
      $query = $this->conn->prepare("SELECT * FROM usuario u WHERE u.username = :user_name and u.password = :pass and u.activo = 1");
      $query->bindParam(":user_name",$user_name);
      $query->bindParam(":pass",$pass);
      $query->execute();
      /*
      $query->debugDumpParams(); // para debugear las consultas!
      die();
      */
      return $query->fetchall();
    }
    /* End of read  functions */

    /* Update functions */
    /* End of update functions */

    /* Delete functions */
    /* End of delete functions */
  }


?>
