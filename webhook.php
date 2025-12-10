<?php

// ----------------------------------------------------------
// CONFIGURACIÓN DEL WEBHOOK
// ----------------------------------------------------------
$VERIFY_TOKEN = "mibot2025"; // TU VERIFY TOKEN
$ACCESS_TOKEN = "EAAeBmNouXW4BQPM04CH9VLgDrjZA4nyiCvBtf3LzS5DPgvbabn9st5sgLy6UW6Wi6UvMbRjy8ZAEuaN5NtPUUCg5mO4uQZAxVlChY22Vx3chYkbut73hqlXjRiHeYzJgzEbOpW8f8JxWh1RcgVs3zDUHJIqOxtpeDlcoHIL2irZBmq2449QZAlzptLyYzKcwF9vijZCqIAQykZCbfhnBkcwfR9YcVbR9ZACzQcuB7SH1WZCUI6OqWJgESaMX8KUf65tObe8UBmTZBOrYd8S59M8khU";
$PHONE_NUMBER_ID = "922340430959332"; // TU PHONE NUMBER ID

// ----------------------------------------------------------
// VERIFICACIÓN DEL WEBHOOK (GET)
// ----------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $mode = $_GET['hub_mode'] ?? null;
    $token = $_GET['hub_verify_token'] ?? null;
    $challenge = $_GET['hub_challenge'] ?? null;

    if ($mode === 'subscribe' && $token === $VERIFY_TOKEN) {
        header('HTTP/1.1 200 OK');
        echo $challenge;
        exit;
    } else {
        header('HTTP/1.1 403 Forbidden');
        echo "Token inválido";
        exit;
    }
}

// ----------------------------------------------------------
// RECEPCIÓN DE MENSAJES (POST)
// ----------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents("php://input"), true);

    // Verifica existencia de mensajes
    if (isset($data["entry"][0]["changes"][0]["value"]["messages"][0])) {

        $message = $data["entry"][0]["changes"][0]["value"]["messages"][0];
        $from = $message["from"]; // Número del usuario
        $text = $message["text"]["body"] ?? ""; // Texto recibido

        // ------------------------------------------------------
        // RESPUESTA AUTOMÁTICA DEL BOT
        // ------------------------------------------------------
        enviarMensajeWhatsApp($from, "👋 Hola! Soy tu bot de compras. ¿En qué puedo ayudarte?");

    }

    header("HTTP/1.1 200 OK");
    echo "EVENT_RECEIVED";
    exit;
}

// ----------------------------------------------------------
// FUNCIÓN PARA ENVIAR MENSAJES A WHATSAPP
// ----------------------------------------------------------
function enviarMensajeWhatsApp($to, $mensaje) {
    global $ACCESS_TOKEN, $PHONE_NUMBER_ID;

    $url = "https://graph.facebook.com/v24.0/" . $PHONE_NUMBER_ID . "/messages";

    $payload = [
        "messaging_product" => "whatsapp",
        "to" => $to,
        "type" => "text",
        "text" => ["body" => $mensaje]
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $ACCESS_TOKEN",
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}

?>







