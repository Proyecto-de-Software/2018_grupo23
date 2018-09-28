<?php
require_once('controller/AppController.php');
class TwigRenderer extends TwigView {

  public function show($view, $params = array() ) {
    $params['loged_user'] = AppController::getInstance()->getUser();
    echo self::getTwig()->render($view, $params);
  }

}

?>
