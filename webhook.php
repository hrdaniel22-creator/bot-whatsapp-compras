<?php

// ========== CONFIGURACIÓN ==========
$VERIFY_TOKEN = "mibot2025";
$ACCESS_TOKEN = "EAAeBmNouXW4BQPM04CH9VLgDrjZA4nyiCvBtf3LzS5DPgvbabn9st5sgLy6UW6Wi6UvMbRjy8ZAEuaN5NtPUUCg5mO4uQZAxVlChY22Vx3chYkbut73hqlXjRiHeYzJgzEbOpW8f8JxWh1RcgVs3zDUHJIqOxtpeDlcoHIL2irZBmq2449QZAlzptLyYzKcwF9vijZCqIAQykZCbfhnBkcwfR9YcVbR9ZACzQcuB7SH1WZCUI6OqWJgESaMX8KUf65tObe8UBmTZBOrYd8S59M8khU";
$PHONE_NUMBER_ID = "922340430959332";

// ================= VERIFICACIÓN DEL WEBHOOK ==================
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    if (
        isset($_GET['hub_verify_token']) &&
        $_GET['hub_verify_token'] === $VERIFY_TOKEN
    ) {
        echo $_GET['hub_challenge'];
        exit;
    } else {
        echo "Token de verificación incorrecto";
        exit;
    }
}

// ==================== RECEPCIÓN DE MENSAJES ====================
$input = file_get_contents("php://input");
$data = json_decode($input, true);

file_put_contents("log.txt", $input); // Guarda lo que envía Meta (para pruebas)

// Si llega un mensaje de usuario
if (isset($data["entry"][0]["changes"][0]["value"]["messages"][0])) {

    $message = $data["entry"][0]["changes"][0]["value"]["messages"][0];
    $from = $message["from"]; 
    $text = $message["text"]["body"] ?? "";

    sendMessage($from, "Hola 👋, recibí tu mensaje: $text");
}

// ==================== FUNCIÓN PARA RESPONDER ====================
function sendMessage($to, $msg) {
    global $ACCESS_TOKEN, $PHONE_NUMBER_ID;

    $url = "https://graph.facebook.com/v24.0/$PHONE_NUMBER_ID/messages";

    $payload = [
        "messaging_product" => "whatsapp",
        "to" => $to,
        "type" => "text",
        "text" => ["body" => $msg]
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $ACCESS_TOKEN",
        "Content-Type: application/json"
    ]);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    file_put_contents("send_log.txt", $response);
}

?>









