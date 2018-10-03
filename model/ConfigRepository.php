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

public function saveConfig($titulo, $email, $descrip, $paginado, $estado){
  $query = $this->conn->prepare("UPDATE configuracion
                                 SET valor = CASE variable
                                               WHEN 'titulo' THEN :titulo
                                               WHEN 'email' THEN :email
                                               WHEN 'descripcion' THEN :descrip
                                               WHEN 'paginado' THEN :paginado
                                               WHEN 'estado' THEN :estado
                                             END");
  $query->bindParam(":titulo",$titulo);
  $query->bindParam(":email",$email);
  $query->bindParam(":descrip",$descrip);
  $query->bindParam(":paginado",$paginado);
  $query->bindParam(":estado",$estado);
  $query->execute();
  }

}

?>
