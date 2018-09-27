<?php

abstract class Connection {

  private $username = "root";
  private $password = "alumno";
	private $host ="127.0.0.1";
  private $db = "trabajo-proyecto-2018";
  protected $conn;

  function __construct(){
    $this->getConnection();
  }

  public function getConnection(){
      $this->conn = new PDO("mysql:dbname=" . $this->db . ";host=" . $this->host . ";charset=utf8", $this->username, $this->password);
  }

}

?>
