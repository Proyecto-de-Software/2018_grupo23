<?php

$botToken="763852469:AAFUJhgWC4xoGEw1DXE3cKPXpROwa5H7V1U";
$website="https://api.telegram.org/bot".$botToken;

$update=file_get_contents('php://input');
$update=json_decode($update,TRUE);

$chatId=$update['message']['chat']['id'];

$message=$update['message']['text'];
$message=explode(" ",$message);
switch ($message[0]) {
  case '/ayuda':
    sendMessage($chatId,'escribiendo /instituciones  Devolverá un listado de Instituciones disponibles');
    sendMessage($chatId,'escribiendo /instituciones-region-sanitaria {id region} Devolverá un listado de Instituciones a
    partir de la región sanitaria indicada por parámetro');
    sendMessage($chatId,'escrbiendo /instituciones {id institución} Devolverá la institución con ese id');
    break;
  case '/instituciones':
    if(empty($message[1])){
      sendMessage($chatId,"Las instituciones son: ");
      $json=json_decode(file_get_contents('https://grupo23.proyecto2018.linti.unlp.edu.ar/api.php/instituciones'));
      foreach ($json as $key => $jsons) {
        $response="";
        foreach($jsons as $key => $value) {
		        if($key=='nombre'){
			           $response.=' '.$value.' ';
		        }
		        if($key=='telefono'){
			           $response.='Telefono: '.$value.' ';
		        }
          }
        sendMessage($chatId,$response);
      }
    }
    else {
      $json=json_decode(file_get_contents('https://grupo23.proyecto2018.linti.unlp.edu.ar/api.php/instituciones/'.$message[1]));
      if(!empty($json)){
        sendMessage($chatId,"La institución es: ");
        foreach ($json as $key => $jsons) {
          $response="";
          foreach($jsons as $key => $value) {
  		        if($key=='nombre'){
  			           $response.=' '.$value.' ';
  		        }
  		        if($key=='telefono'){
  			           $response.='Telefono: '.$value.' ';
  		        }
            }
          sendMessage($chatId,$response);
        }
      }
      else{
        sendMessage($chatId,"No se encontró una institución con ese ID");
      }
    }
    break;
  case '/instituciones-region-sanitaria':
    if(!empty($message[1])){
      $json=json_decode(file_get_contents('https://grupo23.proyecto2018.linti.unlp.edu.ar/api.php/instituciones/region-sanitaria/'.$message[1]));
      if(!empty($json)){
        sendMessage($chatId,"Las instituciones son: ");
        foreach ($json as $key => $jsons) {
          $response="";
          foreach($jsons as $key => $value) {
  		        if($key=='nombre'){
  			           $response.=' '.$value.' ';
  		        }
  		        if($key=='telefono'){
  			           $response.='Telefono: '.$value.' ';
  		        }
            }
          sendMessage($chatId,$response);
        }
      }
      else{
        sendMessage($chatId,"No se encontró una institución con ese ID de region sanitaria");
      }
    }
    else{
      sendMessage($chatId,'Te faltó indicar la region sanitaria');
    }
    break;
  default:
  $response='ese no es un comando válido. escribe /ayuda para obtener los comandos válidos';
  sendMessage($chatId,$response);
    break;
}

function sendMessage($chatId,$response){
$url=$GLOBALS[website].'/sendMessage?chat_id='.$chatId.'&parse_mode=HTML&text='.urlencode($response);
file_get_contents($url);
}
