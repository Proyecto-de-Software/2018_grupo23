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

public function saveConfig($titulo, $email, $descrip, $paginado, $estado, $col_uno, $col_dos, $col_tres){
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
                                             END");
  $query->bindParam(":titulo",$titulo);
  $query->bindParam(":email",$email);
  $query->bindParam(":descrip",$descrip);
  $query->bindParam(":paginado",$paginado);
  $query->bindParam(":estado",$estado);
  $query->bindParam(":col_uno",$col_uno);
  $query->bindParam(":col_dos",$col_dos);
  $query->bindParam(":col_tres",$col_tres);  
  $query->execute();
  }

}

?>
