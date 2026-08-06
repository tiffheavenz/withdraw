<?php

ini_set('display_errors',1);
error_reporting(E_ALL);


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


foreach($bots as $bot){


$url = "https://api.telegram.org/bot".$bot['token']."/sendMessage";


$data = [

"chat_id"=>$bot['chat_id'],

"text"=>"🔥 TEST MESSAGE\n\nBot connection working"

];


$ch=curl_init($url);


curl_setopt($ch,CURLOPT_POST,true);

curl_setopt($ch,CURLOPT_POSTFIELDS,$data);

curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);


$response=curl_exec($ch);


curl_close($ch);



echo "<pre>";
echo "BOT ".$bot['chat_id']."\n";
echo $response;
echo "\n\n";
echo "</pre>";

}

?>
