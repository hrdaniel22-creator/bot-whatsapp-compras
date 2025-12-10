<?php

// ==== CONFIGURACIÓN ====
$VERIFY_TOKEN = "mibot2025";
$ACCESS_TOKEN = "EAAeBmNouXW4BQPM04CH9VLgDrjZA4nyiCvBtf3LzS5DPgvbabn9st5sgLy6UW6Wi6UvMbRjy8ZAEuaN5NtPUUCg5mO4uQZAxVlChY22Vx3chYkbut73hqlXjRiHeYzJgzEbOpW8f8JxWh1RcgVs3zDUHJIqOxtpeDlcoHIL2irZBmq2449QZAlzptLyYzKcwF9vijZCqIAQykZCbfhnBkcwfR9YcVbR9ZACzQcuB7SH1WZCUI6OqWJgESaMX8KUf65tObe8UBmTZBOrYd8S59M8khU";
$PHONE_NUMBER_ID = "922340430959332";

// ==== VERIFICACIÓN DEL WEBHOOK ====
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($_GET['hub_verify_token'] === $VERIFY_TOKEN) {
        echo $_GET['hub_challenge'];
        exit;
    } else {
        echo "Token de verificación incorrecto";
        exit;
    }
}

// ==== RECEPCIÓN DE MENSAJES ====
$body = file_get_contents("php://input");
$data = json_decode($body, true);

file_put_contents("log.txt", $body . PHP_EOL, FILE_APPEND);

if (isset($data["entry"][0]["changes"][0]["value"]["messages"][0])) {
    
    $message = $data["entry"][0]["changes"][0]["value"]["messages"][0];
    $from = $message["from"];
    $text = $message["text"]["body"] ?? "";

    // ========= RESPUESTA AUTOMÁTICA =========
    $url = "https://graph.facebook.com/v24.0/" . $PHONE_NUMBER_ID . "/messages";

    $payload = [
        "messaging_product" => "whatsapp",
        "to" => $from,
        "type" => "text",
        "text" => [
            "body" => "Hola 👋, soy el bot de compras Jalisco.\nRecibí tu mensaje: '$text'"
        ]
    ];

    $options = [
        "http" => [
            "header" => "Content-type: application/json\r\nAuthorization: Bearer $ACCESS_TOKEN\r\n",
            "method" => "POST",
            "content" => json_encode($payload)
        ]
    ];

    $context = stream_context_create($options);
    file_get_contents($url, false, $context);
}

echo "EVENT_RECEIVED";








