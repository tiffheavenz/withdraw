```php
<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

/* ================= TELEGRAM ================= */

$botToken = "8896732586:AAG2boPOp7mteDed11I2j7PYRn6L-Ln-3vQ";
$chatId   = "8940716704";

/* ================= RECEIVE PAYLOAD ================= */

$payload = file_get_contents("php://input");

if (empty(trim($payload))) {
    exit("No payload received");
}

/* ================= DECODE JSON ================= */

$data = json_decode($payload, true);

if (json_last_error() !== JSON_ERROR_NONE) {

    file_get_contents(
        "https://api.telegram.org/bot{$botToken}/sendMessage?" .
        http_build_query([
            "chat_id" => $chatId,
            "text" => "❌ INVALID JSON RECEIVED\n\n" . $payload
        ])
    );

    exit("Invalid JSON");
}

/* ================= VALUES ================= */

$userId    = $data['user_id'] ?? 'N/A';
$name      = $data['name'] ?? 'N/A';
$amount    = (float)($data['amount'] ?? 0);
$fee       = (float)($data['fee'] ?? 0);
$netAmount = (float)($data['net_amount'] ?? 0);
$reference = $data['reference'] ?? 'N/A';
$status    = strtoupper($data['status'] ?? 'PENDING');
$time      = date("Y-m-d H:i:s");

/* ================= TELEGRAM MESSAGE ================= */

$message  = "💸 WITHDRAWAL REQUEST\n\n";
$message .= "👤 User ID: ".$userId."\n";
$message .= "🧑 Name: ".$name."\n";
$message .= "💰 Amount: UGX ".number_format($amount)."\n";
$message .= "💸 Fee: UGX ".number_format($fee)."\n";
$message .= "✅ Net Amount: UGX ".number_format($netAmount)."\n";
$message .= "📌 Reference: ".$reference."\n";
$message .= "📋 Status: ".$status."\n";
$message .= "🕒 Time: ".$time;

/* ================= SEND TO TELEGRAM ================= */

file_get_contents(
    "https://api.telegram.org/bot{$botToken}/sendMessage?" .
    http_build_query([
        "chat_id" => $chatId,
        "text" => $message
    ])
);

echo "WITHDRAWAL RECEIVED";
?>
```
