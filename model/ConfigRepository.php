<?php

require_once('core/Connection.php');

/**
* Repositorio de Configuración
*/
class ConfigRepository extends Connection{

  function __construct(){
    parent::__construct();
  }

  public function getParameters(){
      $query = $this->conn->prepare("SELECT * FROM configuracion");
      $query->execute();
      return $query->fetchall();
  }

  public function saveConfig($titulo, $email, $descrip, $paginado, $estado, $col_uno, $col_dos, $col_tres, $tit_col_uno, $tit_col_dos, $tit_col_tres){
    $query = $this->conn->prepare("UPDATE configuracion
                                   SET valor = CASE variable
                                                 WHEN 'titulo' THEN :titulo
                                                 WHEN 'email' THEN :email
                                                 WHEN 'descripcion' THEN :descrip
                                                 WHEN 'paginado' THEN :paginado
                                                 WHEN 'estado' THEN :estado
                                                 WHEN 'columna_uno' THEN :col_uno
                                                 WHEN 'columna_dos' THEN :col_dos
                                                 WHEN 'columna_tres' THEN :col_tres
                                                 WHEN 'titulo_col_uno' THEN :col_uno_titulo
                                                 WHEN 'titulo_col_dos' THEN :col_dos_titulo
                                                 WHEN 'titulo_col_tres' THEN :col_tres_titulo
                                               END");
    $query->bindParam(":titulo",$titulo);
    $query->bindParam(":email",$email);
    $query->bindParam(":descrip",$descrip);
    $query->bindParam(":paginado",$paginado);
    $query->bindParam(":estado",$estado);
    $query->bindParam(":col_uno",$col_uno);
    $query->bindParam(":col_dos",$col_dos);
    $query->bindParam(":col_tres",$col_tres);
    $query->bindParam(":col_uno_titulo",$tit_col_uno);
    $query->bindParam(":col_dos_titulo",$tit_col_dos);
    $query->bindParam(":col_tres_titulo",$tit_col_tres);
    $query->execute();
  }

  public function getAllRoles(){
    $query=$this->conn->prepare("SELECT * FROM rol");
    $query->execute();
    return $query->fetchall();
  }

  public function getRole($id){
    $query= $this->conn->prepare("SELECT * FROM rol WHERE id=:id");
    $query->bindParam(":id",$id);
    $query->execute();
    return $query->fetchall();
  }

  public function getPermsFromRole($id){
    $query = $this->conn->prepare("SELECT permiso.nombre as permiso FROM rol INNER JOIN rol_tiene_permiso rp ON rol.id = rp.rol_id INNER JOIN permiso ON rp.permiso_id = permiso.id WHERE rol.id=:id");
    $query->bindParam(":id",$id);
    $query->execute();
    return $query->fetchall();
  }

  public function checkRoleName($nombre, $rol_id){
    $query= $this->conn->prepare("SELECT * FROM rol WHERE nombre=:nombre");
    $query->bindParam(":nombre", $nombre);
    $query->execute();
    $valid= $query->fetchall();
    if(empty($valid)) {//no hay ningun rol con ese nombre
      return true;
    }
    else{//existe ese nombre de rol
      if ($valid[0]['id'] == $rol_id){//es ese mismo rol, todo ok
        return true;
      }else{//otro rol tiene ese nombre
        return false;
      }
    }
  }

  public function existsUsersWithRole($rol_id){
    $query= $this->conn->prepare("SELECT * FROM usuario_tiene_rol WHERE rol_id=:id");
    $query->bindParam(":id", $rol_id);
    $query->execute();
    return $query->fetchall();
  }

  public function newRole($nombre, $perms= NULL){
    $query= $this->conn->prepare("INSERT INTO rol(nombre) VALUES(:nombre)");
    $query->bindParam(":nombre", $nombre);
    $query->execute();
    $rol_id = $this->conn->lastInsertId();
    if(!empty($perms)){
      $this->assignPerms($rol_id, $perms);
    }
  }

  function assignPerms($rol_id, $perms){
    for ($i=0; $i < count($perms) ; $i++){
      $perms_id_query=$this->conn->prepare("SELECT id FROM permiso WHERE nombre=:permiso");
      $perms_id_query->bindParam(":permiso",$perms[$i]);
      $perms_id_query->execute();
      $perm_id= $perms_id_query->fetchall();
      $query= $this->conn->prepare("INSERT INTO rol_tiene_permiso(rol_id,permiso_id) VALUES(:rol_id,:permiso_id)");
      $query->bindParam(":rol_id",$rol_id);
      $query->bindParam(":permiso_id",$perm_id[0]['id']);
      $query->execute();
    }
  }

  public function updateRole($rol_id, $nombre, $perms=array()){
    $query= $this->conn->prepare("UPDATE rol SET nombre=:nombre WHERE id=:id");
    $query->bindParam(":nombre", $nombre);
    $query->bindParam(":id", $rol_id);
    $query->execute();
    $this->removePermsFromRol($rol_id);
    if(!empty($perms)) {
      $this->assignPerms($rol_id, $perms);
    }
  }

  /* Delete functions */
  public function removePermsFromRol($id){
    $query= $this->conn->prepare("DELETE FROM rol_tiene_permiso WHERE rol_id=:id");
    $query->bindParam(":id", $id);
    $query->execute();
  }

  public function removeRole($rol_id){
    $this->removePermsFromRol($rol_id);
    $query= $this->conn->prepare("DELETE FROM rol WHERE id=:id");
    $query->bindParam(":id", $rol_id);
    $query->execute();
  }
  /* End of delete functions */

}

?>
