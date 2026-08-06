<?php

ini_set('display_errors',1);
error_reporting(E_ALL);


/* ================= TELEGRAM BOTS ================= */

$bots = [

[
"token"=>"8677498486:AAFyHSstosvrtaBJwj-_eV25U3eWKkbKwOo",
"chat_id"=>"8940716704"
],

[
"token"=>"8565074370:AAFz_Opi7kYiAJc5ptVHhsxNzIEPAZIYUpU",
"chat_id"=>"8938414761"
]

];



/* ================= RECEIVE PAYLOAD ================= */

$payload = file_get_contents("php://input");



/* LOG EVERYTHING RECEIVED */

file_put_contents(
    "render_debug.txt",
    date("Y-m-d H:i:s")."\n".$payload."\n\n",
    FILE_APPEND
);



if (!$payload || trim($payload)=="") {

    exit("EMPTY PAYLOAD");

}



/* ================= EXTRACT MESSAGE ================= */

$data = json_decode($payload,true);


$message = "";



if(is_array($data) && isset($data['message'])) {

    $message = trim($data['message']);

} else {

    $message = trim($payload);

}



/* ================= STOP EMPTY ================= */

if($message==""){

    exit("EMPTY MESSAGE");

}



/* ================= SEND TELEGRAM ================= */

foreach($bots as $bot){


    $url="https://api.telegram.org/bot".
    $bot['token'].
    "/sendMessage";


    $post=[

        "chat_id"=>$bot['chat_id'],

        "text"=>$message

    ];



    $ch=curl_init($url);


    curl_setopt_array($ch,[

        CURLOPT_POST=>true,

        CURLOPT_POSTFIELDS=>$post,

        CURLOPT_RETURNTRANSFER=>true,

        CURLOPT_TIMEOUT=>15

    ]);



    $response=curl_exec($ch);


    file_put_contents(
        "telegram_debug.txt",
        date("Y-m-d H:i:s").
        " ".$bot['chat_id']." ".
        $response."\n",
        FILE_APPEND
    );


    curl_close($ch);

}



echo "SENT";

?>
