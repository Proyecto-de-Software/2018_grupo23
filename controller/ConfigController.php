<?php

require_once('model/ConfigRepository.php');

/**
 * Controlador de la Configuración del sistema
*/
class ConfigController extends MainController {
  protected static $instance;
  protected static $twig;

  public function viewSystemConfig($state= NULL, $msg=""){
    if(!is_null(AppController::getInstance()->getUser())){
      if(AppController::getInstance()->checkPermissions($_GET['action'])){
        $_GET['action']='';
        $param = array();
        if(!is_null($state)){
          $param[$state]= $msg;
        }
        $this::$twig->show('config.html', $param);
      }else{//no tiene permiso para realizar esta acción
        $this->redirectHome();
      }
    }else{//no es un usuario logueado
      $this->redirectHome();
    }
  }

  public function saveConfig(){
    if(!is_null(AppController::getInstance()->getUser())){
      if(AppController::getInstance()->checkPermissions($_GET['action'])){
        if($this->postElementsCheck(array('titulo', 'email', 'descripcion', 'paginado', 'estado'))){
          $this->prepareData(array('titulo', 'email', 'descripcion', 'paginado', 'estado',
                                   'col1_text', 'col2_text', 'col3_text',
                                   'col1_title', 'col2_title', 'col3_title'));
          $query=new ConfigRepository();
          $query->saveConfig($_POST["titulo"],$_POST["email"],$_POST["descripcion"],$_POST["paginado"],$_POST["estado"],$_POST["col1_text"],$_POST["col2_text"],$_POST["col3_text"],
                                $_POST["col1_title"],$_POST["col2_title"],$_POST["col3_title"]);
          $this->viewSystemConfig('success', 'Configuración guardada');
        }else{
          $this->viewSystemConfig('error', 'Se produjo un error: debe completar todas las opciones');
        }
      }else{
          $this->redirectHome();
      }
    }else{$this->redirectHome();}
  }

  //obtengo todos los parámetros de configuración del sistema
  public function getConfigParameters(){
    $query= new ConfigRepository();
    $config= $query->getParameters();
    return array('titulo'=>$config[0][2],
                  'email'=>$config[1][2],
                  'descripcion'=>$config[2][2],
                  'estado'=>$config[3][2],
                  'paginado'=>$config[4][2],
                  'columna_uno'=>$config[5][2],
                  'columna_dos'=>$config[6][2],
                  'columna_tres'=>$config[7][2],
                  'titulo_col_uno'=>$config[8][2],
                  'titulo_col_dos'=>$config[9][2],
                  'titulo_col_tres'=>$config[10][2]);
  }

  public function viewRolesConfig($state= NULL, $msg=""){
    if(!is_null(AppController::getInstance()->getUser())){
      if(AppController::getInstance()->checkPermissions($_GET['action'])){
        $config_repo= new ConfigRepository();
        $_GET['action']='';
        $param = array();
        if(!is_null($state)){
          $param[$state]= $msg;
        }
        $param['roles']= $config_repo->getAllRoles();
        $param['permisos_usuario']= $_SESSION['permissions'];
        $param['permisos_roles']= array("Ver listado"=> "index",
                                        "Crear"=> "new",
                                        "Ver"=> "show",
                                        "Eliminar"=> "destroy",
                                        "Editar"=> "update");
        $this::$twig->show('list_roles.html', $param);
      }else {
        $this->redirectHome();
      }
    }else {
      $this->redirectHome();
    }
  }

  public function viewRole(){
    if(AppController::getInstance()->checkPermissions($_GET['action'])){
      $_GET['action']='';
      $config_repo=new ConfigRepository();
      $rol_id=$_POST['id'];
      $data=array();
      $data['rol']= $config_repo->getRole($rol_id);
      $data['perms']= $config_repo->getPermsFromRole($rol_id);
      if(!empty($data)){
          echo json_encode($data);
      }else {
        $this->viewRolesConfig('error', 'Se produjo un error');
      }
    }else {
      $this->redirectHome();
    }
  }

  public function addRole(){
    if(!is_null(AppController::getInstance()->getUser())){
      if(AppController::getInstance()->checkPermissions($_GET['action'])){
        if($this->checkToken('rol_new')){
          $this->prepareData(array('nombre'));
          if($this->postElementsCheck(array('nombre'))){
            $config_repo= new ConfigRepository();
            if($config_repo->checkRoleName($_POST['nombre'], $_POST['rol_id'])){
              if (isset($_POST['rol_perms'])) {
                $config_repo->newRole($_POST['nombre'], $_POST['rol_perms']);
              }else {
                $config_repo->newRole($_POST['nombre']);
              }
              $this->viewRolesConfig('success', 'El rol fue agregado exitosamente');
            }else {
              $this->viewRolesConfig('error', 'Se produjo un error: Ese rol ya existe');
            }
          }else {
            $this->viewRolesConfig('error', 'Se produjo un error: Faltó completar algún dato');
          }
        }else {
          $this->viewRolesConfig('error', 'Se produjo un error: Token no válido');
        }
      }else {//no tiene permiso para esta acción
        $this->redirectHome();
      }
    }else {//no es un usuario logueado
      $this->redirectHome();
    }
  }

  public function updateRole(){
    if(!is_null(AppController::getInstance()->getUser())){
      if(AppController::getInstance()->checkPermissions($_GET['action'])){
        if($this->checkToken('rol_new')){
          $this->prepareData(array('nombre'));
          if($this->postElementsCheck(array('nombre'))){
            $config_repo= new ConfigRepository();
            if($config_repo->checkRoleName($_POST['nombre'], $_POST['rol_id'])){
              if (isset($_POST['rol_perms'])) {
                $config_repo->updateRole($_POST['rol_id'], $_POST['nombre'], $_POST['rol_perms']);
              }else {
                $config_repo->updateRole($_POST['rol_id'], $_POST['nombre']);
              }
              $this->viewRolesConfig('success', 'El rol fue actualizado exitosamente');
            }else {
              $this->viewRolesConfig('error', 'Se produjo un error: Ya existe un rol con ese nombre');
            }
          }else {
            $this->viewRolesConfig('error', 'Se produjo un error: Faltó completar algún dato');
          }
        }else {
          $this->viewRolesConfig('error', 'Se produjo un error: Token no válido');
        }
      }else {//no tiene permiso para esta acción
        $this->redirectHome();
      }
    }else {//no es un usuario logueado
      $this->redirectHome();
    }
  }

  public function deleteRole(){
    if(!is_null(AppController::getInstance()->getUser())){
      if(AppController::getInstance()->checkPermissions($_GET['action'])){
          $config_repo= new ConfigRepository();
          if(empty($config_repo->existsUsersWithRole($_POST["id_rol"]))){
            $config_repo->removeRole($_POST['id_rol']);
            $this->viewRolesConfig('success', 'El rol fue eliminado');
          }else {
            $this->viewRolesConfig('error', 'Se produjo un error: existen usuarios con ese rol, no es posible eliminarlo.');
          }
      }else {
        $this->redirectHome();
      }
    }else {
      $this->redirectHome();
    }
  }

}

?>
