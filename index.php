<?php

/*TWIG*/
require_once('core/TwigView.php');
require_once('core/TwigRenderer.php');
/*DISPATCHER*/
require_once('core/Dispatcher.php');

DEFINE('DS', DIRECTORY_SEPARATOR); //separador para multiples OS

session_start();

$action=isset($_GET['action'])? $_GET['action'] :'home'; /* si el action esta seteado asigno el valor del get al action y sino es home */

Dispatcher::$action();

?>