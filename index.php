<?php

/*TWIG*/
require_once('core/TwigView.php');
require_once('core/TwigRenderer.php');
/*DISPATCHER*/
require_once('core/Dispatcher.php');
/*REPOSITORY*/
require_once('core/Connection.php');

DEFINE('DS', DIRECTORY_SEPARATOR); //separador para multiples OS

session_start();

$action=isset($_GET['action'])? $_GET['action'] :'home'; /* si el action esta seteado asigno el valor del get al action y sino es home */


  Dispatcher::$action();


/*
catch (Throwable $t) {
    echo $t->getMessage(); //esto se agrega despues, es para evitar actions no validos
}
*/
?>
