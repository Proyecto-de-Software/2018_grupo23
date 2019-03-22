<?php
// Get cURL resource
$curl = curl_init();
// Set some options - we are passing in a useragent too here
$body = json_encode(array("username" => "asd", "password" => "123123123"));
curl_setopt_array($curl, [
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'http://localhost:8000/login_check',
    CURLOPT_POST => 1,
    CURLOPT_HTTPHEADER => array("Content-Type" => "application/json"),
    $body
]);
// Send the request & save response to $resp
$resp = curl_exec($curl);
echo $resp;
// Close request to clear up some resources
curl_close($curl);

?>