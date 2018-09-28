<?php
  require_once('model/UserRepository.php');
/* objetos que se encarga de manejar los permisos y la seguridad*/
class UserIsBlocked extends Exception { }

class User{

    public static function newSession($user_data){ //si la session es nueva la creo
      $instance = new self();
      $instance->setUserData($user_data);
      $instance->setRollAndPermissions();
      return $instance;
    }

    public static function recoverSession(){ //si la session ya existia la recupero
      $instance = new self();
      return $instance;
    }

    private function setUserData($user_data){

          if( $user_data['activo'] == 0 ){
            throw new UserIsBlocked('La cuenta del usuario esta bloqueda');
          }

          $_SESSION['id'] = $user_data['id'];
          $_SESSION['email'] = $user_data['email'];
          $_SESSION['username'] = $user_data['username'];
          $_SESSION['updated_at'] = $user_data['updated_at'];
          $_SESSION['first_name'] = $user_data['first_name'];
          $_SESSION['last_name'] = $user_data['last_name'];

    }

    private function setRollAndPermissions(){
      $user_repo = new UserRepository;
      $user_data = $user_repo->getRolAndPermisosFromUsuario($this->id);
      $_SESSION['permissions'] = array();
      foreach ($user_data as $element){
        array_push($_SESSION['permissions'],$element['permiso']);
      }
      $_SESSION['rol'] = $element['rol'];
    }

    public function isUpToDate(){ // me devuelve si los datos que tengo estan actualisados
      $user_repo = new UserRepository;
      $user_data = $user_repo->getUsuario($_SESSION['id'])[0];
      return ($_SESSION['updated_at'] == $user_data['updated_at']);
    }

    public function updateUser(){ //actualisa los datos del usuario
      $user_repo = new UserRepository;
      $user_data = $user_repo->getUsuario($_SESSION['id'])[0];
      $this->setUserData($user_data);
      $this->setRollAndPermissions();
    }

    public function getPermissions(){ //devuelve los permisos del usuario
      if(!$this->isUpToDate()){
        $this->updateUser();
      }
      return $_SESSION['permissions'];
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
      if(is_null($_SESSION['id'])){ //si no hay ninguna sesion, el objeto user no existe
        $this->user = NULL;
      }else{
        $this->user = User::recoverSession();//si habia una session lo recupero
      }
    }

    public function startUserSession($user_data){
        session_destroy();
        session_set_cookie_params(0);
        session_start();
        $this->user = User::newSession($user_data);
    }

    public function endUserSession(){
      $this->user = NULL;
      session_destroy();
      $_SESSION = array();
    }

    public function checkPermissions($permission){ //chequea si tengo permisos para hacer esa accion

      try{
        $permissions = $this->user->getPermissions();
        return in_array($permission,$permissions);
      }

      catch (\UserIsBlocked $e){ //si el usuario fue bloqueado lo saca de la session
        $this->endUserSession();
        return false;
      }
  }

  public function getUser(){ // me devuelve el usuario que esta logeado
    if( is_null($this->user) ){
      return $this->user;
    }

    elseif($this->user->isUpToDate()){
      return $this->user;
    }

    else{

      try{
        $this->user->updateUser();
        return $this->user;
      }
      catch (\UserIsBlocked $e){ //si el usuario fue bloqueado lo saca de la session
       $this->endUserSession();
        return NULL;
      }

    }
  }

}


?>
