<?php

// -----------------------------------------------------
// ⚠️ CONFIGURACIÓN – REEMPLAZA ESTOS DATOS
// -----------------------------------------------------

$VERIFY_TOKEN = "mibot2025";  // <-- tu verify token
$ACCESS_TOKEN = "TU_ACCESS_TOKEN_AQUI";  // <-- NO pegues tu token real en ChatGPT
$PHONE_NUMBER_ID = "922340430959332";  // <-- tu Phone Number ID oficial

// -----------------------------------------------------
// VERIFICACIÓN DEL WEBHOOK (GET)
// -----------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $mode = $_GET['hub_mode'] ?? '';
    $token = $_GET['hub_verify_token'] ?? '';
    $challenge = $_GET['hub_challenge'] ?? '';

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

// -----------------------------------------------------
// RECEPCIÓN DE MENSAJES (POST)
// -----------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents('php://input'), true);

    if (!empty($data["entry"][0]["changes"][0]["value"]["messages"][0])) {

        $message = $data["entry"][0]["changes"][0]["value"]["messages"][0];
        $from = $message["from"];     
        
        // Texto recibido
        $text = $message["text"]["body"] ?? "";

        // Respuesta automática simple
        enviarMensaje($from, "Hola 👋, recibí tu mensaje: $text");
    }

    header("HTTP/1.1 200 OK");
    echo "EVENT_RECEIVED";
    exit;
}

// -----------------------------------------------------
// FUNCIÓN PARA ENVIAR MENSAJES A WHATSAPP
// -----------------------------------------------------
function enviarMensaje($to, $texto) {
    global $ACCESS_TOKEN, $PHONE_NUMBER_ID;

    $url = "https://graph.facebook.com/v24.0/$PHONE_NUMBER_ID/messages";

    $payload = [
        "messaging_product" => "whatsapp",
        "to" => $to,
        "type" => "text",
        "text" => [ "body" => $texto ]
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $ACCESS_TOKEN",
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}
?>








