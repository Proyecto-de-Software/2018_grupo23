<?php

$botToken="763852469:AAFUJhgWC4xoGEw1DXE3cKPXpROwa5H7V1U";
$website="https://api.telegram.org.bot".$botToken;

$update=file_get_contents('php://input');
$update=json_decode($update,TRUE);

$chatId=$update['message']['chat']['id'];

$message=$update['message']['text'];

switch ($message) {
  case '/ayuda':
    $response='yo te ayudo np';
    sendMessage($chatId,$response);
    break;

  default:
    // code...
    break;
}

function sendMessage($chatId,$response){
$url=$GLOBALS['website'].'/sendMessage?chat_id='.$chatId.'&parse_mode=HTML&text='.urlencode($response);
file_get_contents($url);
}
