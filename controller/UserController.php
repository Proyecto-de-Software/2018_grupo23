<?php

require_once('model/UserRepository.php');

/**
 * Controlador del usuario
 */
class UserControler extends MainController{
  protected static $instance;
  protected static $twig;

  function viewLogin($error = NULL){
    //TODO agregar checkeo de que ya esta logeado
    $param = array();
    if(is_null($error)){
      $param['error'] = $error;
    }
    $this::$twig->show('login.html',$param);
  }

  function login(){
    //TODO agregar checkeo de que ya esta logeado
    if($this->postElementsCheck( array('user_name','password') ) ){ //check de los parametros pasados por post
      $user_repo = new UserRepository();
      $user = $user_repo->loginUsuario($_POST['user_name'],$_POST['password']);
      if(!empty($user)){
        $_SESSION["id"]=$user[0]["id"];
        $_SESSION["username"]=$user[0]["username"];
        $this->redirectHome();
        return;

      }else{$this->viewLogin("Email o Contraseña incorrecta");}
    }else{$this->viewLogin("Email o Contraseña incorrecta");}
  }
}


?>
