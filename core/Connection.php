<?php

abstract class Connection {

  private $username = "grupo23";
  private $password = "ZjJjODE4MTY5M2U3";
	private $host ="localhost";
  private $db = "grupo23";
  protected $conn;

  function __construct(){
    $this->getConnection();
  }

  public function getConnection(){
      $this->conn = new PDO("mysql:dbname=" . $this->db . ";host=" . $this->host . ";charset=utf8", $this->username, $this->password);
  }

}

?>
