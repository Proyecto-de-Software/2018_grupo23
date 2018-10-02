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

public function saveConfig($titulo, $email, $descrip, $cant_elems, $estado){
  $query = $this->conn->prepare("UPDATE configuracion
                                 SET valor = CASE variable
                                               WHEN 'titulo' THEN :titulo
                                               WHEN 'email' THEN :email
                                               WHEN 'descripcion' THEN :descrip
                                               WHEN 'elementos_por_pagina' THEN :cant_elems
                                               WHEN 'estado_sitio' THEN :estado
                                             END");
  $query->bindParam(":titulo",$titulo);
  $query->bindParam(":email",$email);
  $query->bindParam(":descrip",$descrip);
  $query->bindParam(":cant_elems",$cant_elems);
  $query->bindParam(":estado",$estado);
  $query->execute();
  }

}

?>
