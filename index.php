<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);


/* ================= TELEGRAM BOTS ================= */

$bots = [

    [
        "token" => "8677498486:AAFyHSstosvrtaBJwj-_eV25U3eWKkbKwOo",
        "chat_id" => "8940716704"
    ],

    [
        "token" => "8565074370:AAFz_Opi7kYiAJc5ptVHhsxNzIEPAZIYUpU",
        "chat_id" => "8938414761"
    ]

];



/* ================= RECEIVE PAYLOAD ================= */

$payload = file_get_contents("php://input");



/* ================= IGNORE EMPTY PINGS ================= */

if (!$payload || trim($payload) == "") {

    exit("IGNORED EMPTY PING");

}



/* ================= GET MESSAGE ================= */

$data = json_decode($payload, true);


$message = "";


// JSON payload with message
if (
    json_last_error() === JSON_ERROR_NONE &&
    isset($data['message']) &&
    trim($data['message']) !== ""
) {

    $message = trim($data['message']);

}


// Raw payload
elseif (
    json_last_error() !== JSON_ERROR_NONE &&
    trim($payload) !== ""
) {

    $message = trim($payload);

}



/* ================= IGNORE PINGS ================= */

// Do not send if no useful content
if ($message == "" || strlen($message) < 5) {

    exit("IGNORED NO DATA");

}



/* ================= SEND TELEGRAM ================= */

foreach ($bots as $bot) {


    $url = "https://api.telegram.org/bot".$bot['token']."/sendMessage";


    $post = [

        "chat_id" => $bot['chat_id'],

        "text" => $message

    ];



    $ch = curl_init($url);


    curl_setopt_array($ch,[

        CURLOPT_POST => true,

        CURLOPT_RETURNTRANSFER => true,

        CURLOPT_POSTFIELDS => $post,

        CURLOPT_TIMEOUT => 10

    ]);



    $response = curl_exec($ch);



    if(curl_errno($ch)){

        error_log(
            "TELEGRAM ERROR ".$bot['chat_id']." : ".
            curl_error($ch)
        );

    } else {

        error_log(
            "TELEGRAM RESPONSE ".$bot['chat_id']." : ".$response
        );

    }


    curl_close($ch);

}



echo json_encode([

    "status"=>"sent",

    "message"=>$message

]);

?>
