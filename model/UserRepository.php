<?php
require_once('core/Connection.php');
  /**
   * Repositorio de usuario
   */

   // $query->debugDumpParams(); // para debugear las consultas!
   // die();

  class UserRepository extends Connection{

    function __construct(){
      parent::__construct();
    }

    /* Create functions */
    public function newUser($email,$username,$password,$first_name,$last_name,$roles=array()){
      $query= $this->conn->prepare("INSERT INTO usuario(email,username,password,activo,created_at,updated_at,first_name,last_name)
                                         VALUES(:email,:username,:password,:activo,:created_at,:updated_at,:first_name,:last_name)");
      $query->bindParam(":email", $email);
      $query->bindParam(":username", $username);
      $query->bindParam(":password", $password);
      $activo= 1;
      $query->bindParam(":activo", $activo);
      $date_now= date('Y-m-d H:i:s');
      $query->bindParam(":created_at", $date_now);
      $query->bindParam(":updated_at", $date_now);
      $query->bindParam(":first_name", $first_name);
      $query->bindParam(":last_name", $last_name);
      $query->execute();
      $user_id = $this->conn->lastInsertId();
      if(!empty($roles)){
        for ($i=0; $i < count($roles) ; $i++){
          // echo "<pre>";
          //   echo print_r($roles);
          // echo "<pre>";
          $rol_id_query=$this->conn->prepare("SELECT id FROM rol WHERE nombre=:rol");
          $rol_id_query->bindParam(":rol",$roles[$i]);
          $rol_id_query->execute();
          $rol_id= $rol_id_query->fetchall();
          $query= $this->conn->prepare("INSERT INTO usuario_tiene_rol(usuario_id,rol_id) VALUES(:user_id,:rol_id)");
          $query->bindParam(":user_id",$user_id);
          $query->bindParam(":rol_id",$rol_id[0]['id']);
          $query->execute();
        }
      }
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
      $query = $this->conn->prepare("SELECT * FROM usuario WHERE id=:id");
      $query->bindParam(":id",$id);
      $query->execute();
      return $query->fetchall();
    }

    public function getRolAndPermisosFromUsuario($id){
      $query = $this->conn->prepare("SELECT rol.nombre as rol, permiso.nombre as permiso FROM usuario u INNER JOIN usuario_tiene_rol ur ON u.id = :id AND ur.usuario_id = u.id LEFT JOIN rol ON rol.id = ur.rol_id LEFT JOIN rol_tiene_permiso rp ON rp.rol_id = rol.id LEFT JOIN permiso ON permiso.id = rp.permiso_id ");
      $query->bindParam(":id",$id);
      $query->execute();
      return $query->fetchall(PDO::FETCH_ASSOC); //evita que devuelva la respuesta duplicada
    }

    public function getRolesFromUsuario($id){
      $query=$this->conn->prepare("SELECT rol.nombre as rol FROM usuario u INNER JOIN usuario_tiene_rol ur ON u.id = :id AND ur.usuario_id = u.id INNER JOIN rol ON rol.id = ur.rol_id");
      $query->bindParam(":id",$id);
      $query->execute();
      return $query->fetchall(PDO::FETCH_ASSOC);
    }

    public function getRoles(){
      $query=$this->conn->prepare("SELECT * FROM rol");
      $query->execute();
      return $query->fetchall();
    }

    public function getAllUsuarios(){
        $query = $this->conn->prepare("SELECT * FROM usuario");
        $query->execute();
        return $query->fetchall();
    }

    public function checkEmail($email){
      $query= $this->conn->prepare("SELECT * FROM usuario WHERE email=:email");
      $query->bindParam(":email",$email);
      $query->execute();
      $valid=$query->fetchAll();
      return empty($valid);
    }


    /* End of read  functions */

    /* Update functions */
    /* End of update functions */

    /* Delete functions */
    public function removeUser($id){
      $query= $this->conn->prepare("DELETE FROM usuario_tiene_rol WHERE usuario_id=:id");
      $query->bindParam(":id", $id);
      $query->execute();
      $query= $this->conn->prepare("DELETE FROM usuario WHERE id=:id");
      $query->bindParam(":id", $id);
      $query->execute();
    }
    /* End of delete functions */
  }


?>
