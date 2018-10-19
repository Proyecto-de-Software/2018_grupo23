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
    if(AppController::getInstance()->checkPermissions($_GET['action'])){
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

  public function addUser(){
    if(!is_null(AppController::getInstance()->getUser())){
      if(AppController::getInstance()->checkPermissions($_GET['action'])){
        if($this->postElementsCheck(array('apellido','nombre','email','password','re_password','username'))){
          if($this->checkToken('usuario_new')){
          if($_POST["password"] == $_POST["re_password"]){
            $user_repo= new UserRepository();
            if($user_repo->checkUserName($_POST['username'])){
              if(isset($_POST['roles'])){
                $user_repo->newUser($_POST['email'],$_POST['username'],$_POST['password'],$_POST['nombre'],$_POST['apellido'],$_POST['roles']);
              }
              else{
                $user_repo->newUser($_POST['email'],$_POST['username'],$_POST['password'],$_POST['nombre'],$_POST['apellido']);
              }
              $this->viewUsersList('success', 'El usuario fue agregado exitosamente');
            }else{
              $this->viewUsersList('error', 'Se produjo un error: el nombre de usuario ingresado ya existe');
            }
          }else{$this->viewUsersList('error', 'Se produjo un error: el password y la confirmación deben coincidir');}
        }else{$this->viewUsersList('error', 'Token csrf invalido');}
      }else{$this->viewUsersList('error', 'Se produjo un error: faltó completar alguno de los datos');}
      }else{$this->redirectHome();}
    }else{$this->redirectHome();}
  }

  public function deleteUser(){
    if(AppController::getInstance()->checkPermissions($_GET['action'])){
      if($_POST['id_user'] != AppController::getInstance()->getUserData()['id']){
        $user_repo= new UserRepository();
        $user_repo->removeUser($_POST['id_user']);
        $this->viewUsersList();
      }else{
        $this->viewUsersList('error', 'Se produjo un error: no puedes eliminar a ese usuario');
      }
    }else{
      $this->redirectHome();
    }
  }

  public function updateUser(){
    if(!is_null(AppController::getInstance()->getUser())){
      if(AppController::getInstance()->checkPermissions($_GET['action'])){
        if($this->postElementsCheck(array('apellido','nombre','email','password','re_password','username'))){
          if($_POST["password"] == $_POST["re_password"]){
            $user_repo= new UserRepository();
            if($user_repo->checkUserName($_POST['username'], $_POST['user_id'])){
              if(isset($_POST['roles'])){
                $user_repo->updateUser($_POST['user_id'],$_POST['email'],$_POST['username'],$_POST['password'],$_POST['nombre'],$_POST['apellido'],$_POST['roles']);
              }
              else{
                $user_repo->updateUser($_POST['user_id'],$_POST['email'],$_POST['username'],$_POST['password'],$_POST['nombre'],$_POST['apellido']);
              }
              $this->viewUsersList('success', 'El usuario fue actualizado exitosamente');
            }else{
              $this->viewUsersList('error', 'Se produjo un error :el nombre de usuario ingresado ya existe');
            }
          }else{
            $this->viewUsersList('error', 'Se produjo un error: el password y la confirmación deben coincidir');
          }
        }else{
          $this->viewUsersList('error', 'Se produjo un error: faltó completar alguno de los datos');
        }
      }else{
        $this->redirectHome();
      }
    }else{
      $this->redirectHome();
    }
  }

  public function blockUser(){
    if(!is_null(AppController::getInstance()->getUser())){
      $_GET['action']='usuario_index';
      if(AppController::getInstance()->checkPermissions('usuario_update')){
        if($_POST['id_user'] != AppController::getInstance()->getUserData()['id']){//no es él mismo...
          $user_repo= new UserRepository();
          $user_repo->blockUser($_POST['id_user']);
          $this->viewUsersList();
        }else {
          $this->viewUsersList('error', 'Se produjo un error: no puedes bloquear a ese usuario');
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
