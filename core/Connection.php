<?php

abstract class Connection {

  private $username = "root";
  private $password = "";
	private $host ="127.0.0.1";
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
