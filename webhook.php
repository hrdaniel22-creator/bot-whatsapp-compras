<?php

$VERIFY_TOKEN = "mibot2025";

// Verificación del webhook (GET)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $mode = $_GET['hub_mode'] ?? null;
    $token = $_GET['hub_verify_token'] ?? null;
    $challenge = $_GET['hub_challenge'] ?? null;

    if ($mode === 'subscribe' && $token === $VERIFY_TOKEN) {
        http_response_code(200);
        echo $challenge;
        exit;
    } else {
        http_response_code(403);
        echo "Token incorrecto";
        exit;
    }
}

// Recepción de mensajes (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents("php://input");
    file_put_contents("log.txt", $input . PHP_EOL, FILE_APPEND);

    http_response_code(200);
    echo "EVENT_RECEIVED";
    exit;
}

// Cualquier otra cosa
http_response_code(404);
echo "Not Found";









