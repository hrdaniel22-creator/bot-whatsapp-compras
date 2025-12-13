<?php
// ================================
// CONFIGURACIÓN
// ================================
$VERIFY_TOKEN = "mibot2025";

// ================================
// VERIFICACIÓN DEL WEBHOOK (GET)
// ================================
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $mode = $_GET['hub_mode'] ?? '';
    $token = $_GET['hub_verify_token'] ?? '';
    $challenge = $_GET['hub_challenge'] ?? '';

    if ($mode === 'subscribe' && $token === $VERIFY_TOKEN) {
        http_response_code(200);
        echo $challenge;
        exit;
    }

    http_response_code(403);
    echo "Token inválido";
    exit;
}

// ================================
// RECEPCIÓN DE MENSAJES (POST)
// ================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $input = file_get_contents("php://input");

    // Guardar logs para depuración
    file_put_contents("log.txt", date("Y-m-d H:i:s") . " " . $input . PHP_EOL, FILE_APPEND);

    http_response_code(200);
    echo "EVENT_RECEIVED";
    exit;
}

// ================================
http_response_code(404);
echo "Not Found";









