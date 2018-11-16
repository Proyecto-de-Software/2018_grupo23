<?php

$botToken="763852469:AAFUJhgWC4xoGEw1DXE3cKPXpROwa5H7V1U";
$website="https://api.telegram.org.bot".$botToken;

$update=file_get_contents('php://input');
$update=json_decode($update,TRUE);

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

function sendMessage($chatId,$message){
$url=$GLOBALS[website].'/sendMessage?chat_id='.$chatId.'&parse_mode=HTML&text='.urlencode($message);
file_get_contents($url);
}
