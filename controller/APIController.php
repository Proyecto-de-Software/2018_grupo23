<?php

//require_once('model/APIRepository.php');

/**
 * Controlador de la API
 */
class APIController extends MainController{
  protected static $instance;
  protected static $twig;

  function api(){
    $botToken="763852469:AAFUJhgWC4xoGEw1DXE3cKPXpROwa5H7V1U";
    $website="https://api.telegram.org.bot".$botToken;

    $update=json_decode(file_get_contents('php://input'),TRUE);

    $chatId=$update['message']['chat']['id'];

    $message=$update['message']['text'];

    switch ($message) {
      case '/ayuda':
        $rensponse='yo te ayudo np';
        sendMessage($chatId,$rensponse);
        break;

      default:
        // code...
        break;
    }

}
function sendMessage($chatId,$message){
  $url=$GLOBALS[website].'/sendMessage?chat_id='.$chatId.'&parse_mode=HTML&text='.urlencode($response);
  file_get_contents($url);
}
}
