<?php

// -------------------------------
// CONFIGURA TU VERIFY TOKEN AQUÍ
// -------------------------------
$VERIFY_TOKEN = "mibot2025";

// -------------------------------------
// 1️⃣ VERIFICACIÓN DEL WEBHOOK (GET)
// -------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $mode = $_GET['hub_mode'] ?? null;
    $token = $_GET['hub_verify_token'] ?? null;
    $challenge = $_GET['hub_challenge'] ?? null;

    if ($mode === 'subscribe' && $token === $VERIFY_TOKEN) {
        // Responder el reto de verificación
        http_response_code(200);
        echo $challenge;
        exit;
    } else {
        http_response_code(403);
        echo "Token no válido";
        exit;
    }
}

// -------------------------------------
// 2️⃣ RECEPCIÓN DE MENSAJES (POST) v24.0
// -------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Obtener JSON enviado por WhatsApp
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);

    // Registrar en logs (opcional)
    file_put_contents("whatsapp_log.txt", $input . PHP_EOL, FILE_APPEND);

    // Validar estructura básica
    if (isset($data["entry"][0]["changes"][0]["value"]["messages"][0])) {

        $msg = $data["entry"][0]["changes"][0]["value"]["messages"][0];

        $from = $msg["from"]; // número del remitente
        $type = $msg["type"]; // tipo de mensaje

        // Mensajes de texto
        if ($type === "text") {
            $body = $msg["text"]["body"];

            // Aquí procesas tu lógica
            file_put_contents(
                "mensajes_recibidos.txt",
                "De: $from - Mensaje: $body" . PHP_EOL,
                FILE_APPEND
            );
        }
    }

    // Respuesta obligatoria
    http_response_code(200);
    echo "EVENT_RECEIVED";
    exit;
}

// -------------------------------------
// SI NO ES GET O POST
// -------------------------------------
http_response_code(404);
echo "Not Found";
exit;

?>






