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
        // PUT YOUR FULL SECOND BOT TOKEN HERE
        "token" => "8565074370:AAFz_Opi7kYiAJc5ptVHhsxNzIEPAZIYUpU",
        "chat_id" => "8938414761"
    ]

];



/* ================= RECEIVE PAYLOAD ================= */

$payload = file_get_contents("php://input");


if (!$payload || trim($payload) == "") {

    exit("NO PAYLOAD");

}



/* ================= JSON ================= */

$data = json_decode($payload, true);


if (json_last_error() !== JSON_ERROR_NONE) {


    $message = "❌ INVALID JSON\n\n".$payload;


} else {


    $userId    = $data['user_id'] ?? 'N/A';
    $name      = $data['name'] ?? 'N/A';
    $amount    = (float)($data['amount'] ?? 0);
    $fee       = (float)($data['fee'] ?? 0);
    $netAmount = (float)($data['net_amount'] ?? 0);
    $reference = $data['reference'] ?? 'N/A';
    $status    = strtoupper($data['status'] ?? 'PENDING');


    $time = date("Y-m-d H:i:s");



    /* ================= MESSAGE ================= */


    $message  = "💸 WITHDRAWAL REQUEST\n\n";
    $message .= "👤 User ID: ".$userId."\n";
    $message .= "🧑 Name: ".$name."\n";
    $message .= "💰 Amount: UGX ".number_format($amount)."\n";
    $message .= "💸 Fee: UGX ".number_format($fee)."\n";
    $message .= "✅ Net Amount: UGX ".number_format($netAmount)."\n";
    $message .= "📌 Reference: ".$reference."\n";
    $message .= "📋 Status: ".$status."\n";
    $message .= "🕒 Time: ".$time;

}





/* ================= SEND TELEGRAM ================= */


foreach($bots as $bot){


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

        CURLOPT_TIMEOUT => 10

    ]);



    $response = curl_exec($ch);



    if(curl_errno($ch)){


        error_log(
            "TELEGRAM CURL ERROR ".$bot['chat_id']." : ".
            curl_error($ch)
        );


    } else {


        error_log(
            "TELEGRAM ".$bot['chat_id']." RESPONSE: ".$response
        );


    }



    curl_close($ch);

}



/* ================= WHATSAPP ================= */


$whatsappPhone = "256755336031";

$whatsappApiKey = "5893046";


file_get_contents(
    "https://api.callmebot.com/whatsapp.php?" .
    http_build_query([

        "phone"=>$whatsappPhone,

        "text"=>$message,

        "apikey"=>$whatsappApiKey

    ])
);



echo "WITHDRAWAL RECEIVED";

?>
