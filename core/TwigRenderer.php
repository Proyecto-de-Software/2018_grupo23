<?php
require_once('controller/MainController.php');
require_once('controller/AppController.php');
require_once('controller/ConfigController.php');
class TwigRenderer extends TwigView {

  public function show($view, $params = array() ) {
    $params['loged_user'] = AppController::getInstance()->getUserData();
    $params['page_config'] = ConfigController::getInstance()->getConfigParameters();
    echo self::getTwig()->render($view, $params);
  }

}

?>
