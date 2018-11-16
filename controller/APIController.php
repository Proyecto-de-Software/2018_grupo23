<?php

require_once('model/APIRepository.php');

/**
 * Controlador de la API
 */
class APIController extends MainController{
  protected static $instance;
  protected static $twig;

  function api(){
    $returnArray = true;
    $rawData = file_get_contents('php://input');
    $response = json_decode($rawData, $returnArray);
    $chatId = $response['message']['chat']['id'];


    // Obtener comando (y sus posibles parametros)
    $regExp = '#^(\/[a-zA-Z0-9\/]+?)(\ .*?)$#i';


    $tmp = preg_match($regExp, $response['message']['text'], $aResults);

    if (isset($aResults[1])) {
        $cmd = trim($aResults[1]);
        $cmd_params = trim($aResults[2]);
    } else {
        $cmd = trim($response['message']['text']);
        $cmd_params = '';
    }

    $msg = array();
    $msg['chat_id'] = $response['message']['chat']['id'];
    $msg['text'] = null;
    $msg['disable_web_page_preview'] = true;
    $msg['reply_to_message_id'] = $response['message']['message_id'];
    $msg['reply_markup'] = null;

    switch ($cmd) {
    case '/start':
        $msg['text']  = 'Hola ' . $response['message']['from']['first_name'] .
                   " Usuario: " . $response['message']['from']['username'] . '!' . PHP_EOL;
        $msg['text'] .= '¿Como puedo ayudarte? /help';
        $msg['reply_to_message_id'] = null;
        break;

    case '/help':
        $msg['text']  = 'Los comandos disponibles son estos:' . PHP_EOL;
        $msg['text'] .= '/start Inicializa el bot' . PHP_EOL;
        $msg['text'] .= '/instituciones devuelve la lista de instituciones disponibles' . PHP_EOL;
        $msg['text'] .= '/instituciones/ +institucion-id devuelve los datos de la institución con ese id' . PHP_EOL;
        $msg['text'] .= '/instituciones/region-sanitaria/ +region-sanitaria-id devuelve el listado de instituciones de la
                        region sanitaria con ese id';
        $msg['reply_to_message_id'] = null;
        break;

    case '/instituciones':
        $msg['text']  = 'Las instituciones son:' . PHP_EOL;
        $msg['text'] .= 'aquí irían mis instituciones... si tuviera alguna :(';
        $msg['reply_to_message_id'] = null;
        break;

    case '/turnos':
        $msg['text']  = 'Los turnos disponibles son: 10:30 | 11:45 | 15:15';
        break;

    default:
            $msg['text']  = 'Lo siento, no es un comando válido.' . PHP_EOL;
            $msg['text'] .= 'Prueba /help para ver la lista de comandos disponibles';
            break;
    }

    $url = 'https://api.telegram.org/bot763852469:AAFUJhgWC4xoGEw1DXE3cKPXpROwa5H7V1U/sendMessage';

    $options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($msg)
        )
    );

    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    exit(0);
  }
}


/* $botToken="763852469:AAFUJhgWC4xoGEw1DXE3cKPXpROwa5H7V1U";
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
} */
