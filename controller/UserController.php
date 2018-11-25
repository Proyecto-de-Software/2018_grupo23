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

  public function viewUser(){
    $app_contr= AppController::getInstance();
    if( $app_contr->checkPermissions('usuario_show') || $app_contr->checkPermissions('usuario_update') ){
      $_GET['action']='';
      $user_repo=new UserRepository();
      $user_id=$_POST["id"];
      $data=array();
      $data['user']=$user_repo->getUsuario($user_id);
      $data['roles']=$user_repo->getRolesFromUsuario($user_id);
      if(!empty($data)){
          echo(json_encode($data));
      }
      else{
        $this->viewUsersList('error', 'Se produjo un error');
      }
    }else{
      $this->redirectHome();
    }
  }

  public function viewUsersList($state=NULL, $msg=""){
    if(!is_null(AppController::getInstance()->getUser())){
      if(AppController::getInstance()->checkPermissions($_GET['action'])){
        $user_repo = new UserRepository();
        $param = array();
        if(!is_null($state)){
              $param[$state]= $msg;
        }
        $users= $user_repo->getAllUsuarios();
        foreach ($users as $key=>$user){
          $roles=$user_repo->getRolesFromUsuario($user['id']);
          if (isset($roles[0])){
            $str='';
            foreach ($roles as $rol){
              $str .= $rol['rol'] .', ';
            }
            $users[$key]['roles']= $str;
          }else{
            $users[$key]['roles']= 'NA';
          }
        }
        $param['users']= $users;
        $param['roles']= $user_repo->getRoles();//para los roles que se muestran en el addUserForm
        $param['permisos']= $_SESSION['permissions'];
        $this::$twig->show('list_users.html', $param);
      }else{
      $this->redirectHome();
      }
    }else{
      $this->redirectHome();
    }
  }

  function isValidForm($surname, $name, $username, $email, $pass, $re_pass){
    $err='';
    if ($this->postElementsCheck(array('apellido','nombre','email','password','re_password','username'))){
      if (!(preg_match("/^[a-z ñáéíóú]{2,60}+$/i", $surname))) {
        $err.= 'error en apellido: se permiten solo letras, espacios y acentos. Mínimo 2 caracteres, máximo 60. ';
      }
      if (!(preg_match("/^[a-z ñáéíóú]{2,60}+$/i", $name))) {
        $err.= 'error en nombre: se permiten solo letras, espacios y acentos. Mínimo 2 caracteres, máximo 60. ';
      }
      if (!filter_var($email, FILTER_VALIDATE_EMAIL) === true) {
        $err.= 'error en email: no es un email válido. ';
      }
      if ( ( strlen($username) < 5 ) || ( strlen($username) > 20 ) || (!ctype_alnum($username) ) ){
        $err.= 'error en nombre de usuario: mínimo 6 caracteres alfanuméricos, máximo 20. ';
      }
      if ( ( strlen($pass) < 8 ) || ( strlen($pass) > 20 ) ) {
        $err.= 'error en password: mínimo 8 caracteres, máximo 20. ';
      }
      if ( $pass != $re_pass ){
        $err.= 'error: password y confirmación deben coincidir. ';
      }
    }else {
      $err.= 'Se produjo un error: faltó completar alguno/s de los datos. ';
    }
    return $err;
  }

  public function addUser(){
    if(!is_null(AppController::getInstance()->getUser())){
      if(AppController::getInstance()->checkPermissions($_GET['action'])){
        if($this->checkToken('usuario_new')){
          $this->prepareData(array('apellido','nombre','email','password','re_password','username'));
          $err= $this->isValidForm($_POST['apellido'],$_POST['nombre'],$_POST['username'],$_POST['email'],$_POST['password'],$_POST['re_password']);
          if(empty($err)){
              $user_repo= new UserRepository();
              if($user_repo->checkUserName($_POST['username'])){
                if($user_repo->checkEmail($_POST['email'])){
                  if(isset($_POST['roles'])){
                    $user_repo->newUser($_POST['email'],$_POST['username'],$_POST['password'],$_POST['nombre'],$_POST['apellido'],$_POST['roles']);
                  }
                  else {
                    $user_repo->newUser($_POST['email'],$_POST['username'],$_POST['password'],$_POST['nombre'],$_POST['apellido']);
                  }
                  $this->viewUsersList('success', 'El usuario fue agregado exitosamente');
                }else {
                  $this->viewUsersList('error', 'Se produjo un error: el email ingresado ya existe');
                }
              }else {
                $this->viewUsersList('error', 'Se produjo un error: el nombre de usuario ingresado ya existe');
              }
          }else {
            $this->viewUsersList('error', $err);
          }
        }else {
          $this->viewUsersList('error', 'Se produjo un error: Token no válido');
        }
      }else {//no tiene permiso para esta acción
        $this->redirectHome();
      }
    }else {//no es un usuario logueado
      $this->redirectHome();
    }
  }

  public function deleteUser(){
    if(AppController::getInstance()->checkPermissions($_GET['action'])){
      if($this->checkToken('usuario_destroy')){
      if($_POST['id_user'] != AppController::getInstance()->getUserData()['id']){
        $user_repo= new UserRepository();
        $user_repo->removeUser($_POST['id_user']);
        $this->viewUsersList('success', 'El usuario fue eliminado');
      }else{
        $this->viewUsersList('error', 'Se produjo un error: no puedes eliminar a ese usuario');
      }
    }else {
      $this->viewUsersList('error','No deberías estar haciendo esto.');
    }
    }else {
      $this->redirectHome();
    }

  }

  public function updateUser(){
    if(!is_null(AppController::getInstance()->getUser())){
      if(AppController::getInstance()->checkPermissions($_GET['action'])){
        if($this->checkToken('usuario_new')){
          $this->prepareData(array('apellido','nombre','email','password','re_password','username'));
          $err= $this->isValidForm($_POST['apellido'],$_POST['nombre'],$_POST['username'],$_POST['email'],$_POST['password'],$_POST['re_password']);
          if(empty($err)){
            $user_repo= new UserRepository();
            if($user_repo->checkUserName($_POST['username'], $_POST['user_id'])){
              if($user_repo->checkEmail($_POST['email'], $_POST['user_id'])){
                if($_POST['user_id'] != AppController::getInstance()->getUserData()['id']){//está viendo a otro usuario
                  if(isset($_POST['roles'])){
                    $user_repo->updateUser($_POST['user_id'],$_POST['email'],$_POST['username'],$_POST['password'],$_POST['nombre'],$_POST['apellido'],$_POST['roles']);
                    $this->viewUsersList('success', 'El usuario se actualizó exitosamente');
                  }else {
                    $user_repo->updateUser($_POST['user_id'],$_POST['email'],$_POST['username'],$_POST['password'],$_POST['nombre'],$_POST['apellido']);
                    $this->viewUsersList('success', 'El usuario se actualizó exitosamente');
                  }
                }else {//soy yo
                  if ( !isset($_POST['roles']) || !in_array('Administrador', $_POST['roles'])  ){//chequeo que no se esté quitando el rol de admin
                    $this->viewUsersList('error', 'No puedes quitarte el rol de Administrador a vos mismo');
                  }else {
                    $user_repo->updateUser($_POST['user_id'],$_POST['email'],$_POST['username'],$_POST['password'],$_POST['nombre'],$_POST['apellido'],$_POST['roles']);
                    $this->viewUsersList('success', 'El usuario se actualizó exitosamente');
                  }
                }
              }else {
                $this->viewUsersList('error', 'Se produjo un error: el email ingresado ya existe');
              }
            }else {
              $this->viewUsersList('error', 'Se produjo un error: el nombre de usuario ingresado ya existe');
            }
          }else {//error con algun campo del form
            $this->viewUsersList('error', $err);
          }
        }else {
          $this->viewUsersList('error', 'Se produjo un error: Token no válido');
        }
      }else {
        $this->redirectHome();
      }
    }else {
      $this->redirectHome();
    }
  }

  public function blockUser(){
    if(!is_null(AppController::getInstance()->getUser())){
      $_GET['action']='usuario_index';
      if(AppController::getInstance()->checkPermissions('usuario_update')){
        if($this->checkToken('usuario_block')){
          if($_POST['id_user'] != AppController::getInstance()->getUserData()['id']){//no es él mismo...
            $user_repo= new UserRepository();
            $state= $user_repo->blockUser($_POST['id_user']);
            if($state == 1){
              $this->viewUsersList('success', 'El usuario fue bloqueado');
            }else {
              $this->viewUsersList('success', 'El usuario fue desbloqueado');
            }
        }else {
          $this->viewUsersList('error', 'Se produjo un error: no puedes bloquear a ese usuario');
        }
      }else {
          $this->viewUsersList('error','No deberías estar haciendo esto.');
      }
      }else {
        $this->viewUsersList('error', 'Se produjo un error: no tienes permiso para realizar esa acción');
      }
    }else {
      $this->redirectHome();
    }
  }

}

?>
