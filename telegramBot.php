<?php

$botToken="763852469:AAFUJhgWC4xoGEw1DXE3cKPXpROwa5H7V1U";
$website="https://api.telegram.org/bot".$botToken;

$update=file_get_contents('php://input');
$update=json_decode($update,TRUE);

$chatId=$update['message']['chat']['id'];

$message=$update['message']['text'];

switch ($message) {
  case '/ayuda':
    $response='yo te ayudo np';
    sendMessage($chatId,$response);
    break;
  case '/instituciones':
    $response=file_get_contents('https://grupo23.proyecto2018.linti.unlp.edu.ar/?action=list_instituciones');
    break;
  else:
  $response='ese no es un comando válido. escribe /ayuda para obtener los comandos válidos';
  sendMessage($chatId,$response);
    break;
}

function sendMessage($chatId,$response){
$url=$GLOBALS[website].'/sendMessage?chat_id='.$chatId.'&parse_mode=HTML&text='.urlencode($response);
file_get_contents($url);
}
