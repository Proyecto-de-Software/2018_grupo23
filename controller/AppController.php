<?php

/* objeto que se encarga de manejar los permisos y la seguridad*/


class User{
    private $id;
    private $email;
    private $username;
    private $activo;
    private $update_at;
    private $first_name;
    private $last_name;

    public function __construct($user_data){
        $this->id = $user_data['id'];
        $this->email = $user_data['email'];
        $this->username = $user_data['username'];
        $this->activo = $user_data['activo'];
        $this->update_at = $user_data['update_at'];
        $this->first_name = $user_data['first_name'];
        $this->last_name = $user_data['last_name'];
    }
}

class AppController{

    private static $instance;
    private $user;

    public static function getInstance(){
        if(!isset(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }

    function __construct(){
        $this->user = NULL;
    }

    public function startUserSession($user_data){
        $this->user = new User($user_data);
    }
}


?>
