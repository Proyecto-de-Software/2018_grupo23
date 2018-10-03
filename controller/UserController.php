<?php

require_once('model/UserRepository.php');

/**
 * Controlador del usuario
 */
class UserController extends MainController{
  protected static $instance;
  protected static $twig;

  function viewLogin($error = NULL){
    $_GET['action']='';
    if(is_null(AppController::getInstance()->getUser())){ //chequeo que no este logeado
      $param = array();
      if(!is_null($error)){
        $param['error'] = $error;
      }
      $this::$twig->show('login.html',$param);
    }else{$this->redirectHome();}
  }

  public function login(){
    if(is_null(AppController::getInstance()->getUser())){ //chequeo que no este logeado
      if($this->postElementsCheck( array('user_name','password') ) ){ //check de los parametros pasados por post
        $user_repo = new UserRepository();
        $user = $user_repo->loginUsuario($_POST['user_name'],$_POST['password']);
        if(!empty($user)){ //si el usuario existe lo logeo
          AppController::getInstance()->startUserSession($user[0]);
          $this->redirectHome();
          $_GET['action']=''; //limpio el action
          return;
        }else{$this->viewLogin("Email o Contraseña incorrecta");}
      }else{$this->viewLogin("Email o Contraseña incorrecta");}
    }else{$this->redirectHome();}
  }

  public function logout() {
    if(!is_null(AppController::getInstance()->getUser())){
      AppController::getInstance()->endUserSession();
    }
    $this->redirectHome();
    $_GET['action']='';
  }

  public function viewUsersList($error = NULL){
    if(!is_null(AppController::getInstance()->getUser())){
      $_GET['action']='';
      $user_repo = new UserRepository();
      $param = array();
      if(!is_null($error)){
        $param['error']= $error;
      }
      $users= $user_repo->getAllUsers();
      $param['users']= $users;
      $this::$twig->show('list_users.html', $param);
    }else{
      $this->redirectHome();
    }
  }

  public function addUser(){
    if(!is_null(AppController::getInstance()->getUser())){
      if($this->postElementsCheck(array('apellido','nombre','email','password','re_password','username'))){
        if($_POST["password"] == $_POST["re_password"]){
          $user_repo= new UserRepository();
          if($user_repo->checkEmail($_POST['email'])){
            $user_repo->newUser($_POST['email'],$_POST['username'],$_POST['password'],$_POST['nombre'],$_POST['apellido']);
            $this->viewUsersList('Success');
          }else{$this->viewUsersList('Hubo un error: el email ingresado ya existe');}
        }else{$this->viewUsersList('Hubo un error: el password y la confirmación deben coincidir');}
      }else{$this->viewUsersList('Hubo un error: faltó completar alguno de los datos');}
    }else{$this->redirectHome();}
  }

}

?>
