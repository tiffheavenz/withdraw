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



/* ================= CHECK PAYLOAD ================= */

if (!$payload || trim($payload) === "") {

    echo json_encode([
        "status" => "ignored",
        "reason" => "empty payload"
    ]);

    exit;

}



/* ================= EXTRACT MESSAGE ================= */

$data = json_decode($payload, true);


$message = "";


// JSON MESSAGE
if (
    json_last_error() === JSON_ERROR_NONE &&
    isset($data['message']) &&
    trim($data['message']) !== ""
) {

    $message = trim($data['message']);

}


// RAW TEXT
elseif (
    json_last_error() !== JSON_ERROR_NONE &&
    trim($payload) !== ""
) {

    $message = trim($payload);

}




/* ================= REMOVE OLD HEADERS ================= */

$message = str_replace(
    [
        "🔥 RENDER MESSAGE RECEIVED",
        "🕒 TIME:",
        "📦 JSON DATA:",
        "```"
    ],
    "",
    $message
);


$message = trim($message);




/* ================= STOP EMPTY DATA ================= */

if ($message === "") {

    echo json_encode([
        "status"=>"ignored",
        "reason"=>"no message data"
    ]);

    exit;

}




/* ================= SEND TELEGRAM ================= */

$results = [];


foreach ($bots as $bot) {


    $url = "https://api.telegram.org/bot".$bot['token']."/sendMessage";


    $postData = [

        "chat_id" => $bot['chat_id'],

        "text" => $message

    ];



    $ch = curl_init($url);


    curl_setopt_array($ch,[

        CURLOPT_POST => true,

        CURLOPT_RETURNTRANSFER => true,

        CURLOPT_POSTFIELDS => $postData,

        CURLOPT_TIMEOUT => 15

    ]);



    $response = curl_exec($ch);



    if (curl_errno($ch)) {


        $results[] = [

            "chat_id" => $bot['chat_id'],

            "status" => "curl_error",

            "error" => curl_error($ch)

        ];


    } else {


        $results[] = [

            "chat_id" => $bot['chat_id'],

            "telegram_response" => json_decode($response,true)

        ];


    }



    curl_close($ch);

}




/* ================= RETURN RESULT ================= */

echo json_encode([

    "status"=>"completed",

    "message_sent"=>$message,

    "results"=>$results

], JSON_PRETTY_PRINT);

?>
