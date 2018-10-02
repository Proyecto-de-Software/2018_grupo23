<?php
require_once('core/Connection.php');
  /**
   * Repositorio de usuario
   */
   /*
   $query->debugDumpParams(); // para debugear las consultas!
   die();
   */
  class UserRepository extends Connection{

    function __construct(){
      parent::__construct();
    }

    /* Create functions */

    /* para agregar un usuario (faltan hacer todos los chequeos, está hecha así nomás) */
    public function newUser($email,$username,$password,$first_name,$last_name){
      $query= $this->conn->prepare("INSERT INTO usuario(email,username,password,created_at,first_name_last_name)
                                           VALUES(:email,:username,:password,:activo,:created_at,:first_name,:last_name)");
      $query->bindParam(":email", $email);
      $query->bindParam(":username", $user_name);
      $query->bindParam(":password", $password);
      $query->bindParam(":activo", 1);
      $query->bindParam(":created_at", NOW());
      $query->bindParam(":first_name", $first_name);
      $query->bindParam(":last_name", $last_name);
      $query->execute();
    }

    /* End of create functions */

    /* READ functions */
    public function loginUsuario($user_name,$pass){
      $query = $this->conn->prepare("SELECT * FROM usuario u WHERE u.username = :user_name and u.password = :pass and u.activo = 1");
      $query->bindParam(":user_name",$user_name);
      $query->bindParam(":pass",$pass);
      $query->execute();
      return $query->fetchall();
    }

    public function getUsuario($id){
      $query = $this->conn->prepare("SELECT * FROM usuario u WHERE u.id = :id");
      $query->bindParam(":id",$id);
      $query->execute();
      return $query->fetchall();
    }

    public function getRolAndPermisosFromUsuario($id){
      $query = $this->conn->prepare("SELECT rol.nombre as rol, permiso.nombre as permiso FROM usuario u INNER JOIN usuario_tiene_rol ur ON u.id = :id AND ur.usuario_id = u.id INNER JOIN rol ON rol.id = ur.rol_id INNER JOIN rol_tiene_permiso rp ON rp.rol_id = rol.id INNER JOIN permiso ON permiso.id = rp.permiso_id");
      $query->bindParam(":id",$id);
      $query->execute();
      return $query->fetchall(PDO::FETCH_ASSOC); //evita que devuelva la respuesta duplicada
    }

    public function getAllUsers(){
        $query = $this->conn->prepare("SELECT first_name,last_name,email FROM usuario");
        $query->execute();
        return $query->fetchall();
    }


    /* End of read  functions */

    /* Update functions */
    /* End of update functions */

    /* Delete functions */
    /* End of delete functions */
  }


?>
