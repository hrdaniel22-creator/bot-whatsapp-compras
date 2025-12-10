<?php

$VERIFY_TOKEN = "mibot2025";

// 1. Verificación GET (cuando configuras el Webhook en Meta)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (
        isset($_GET['hub.mode']) && $_GET['hub.mode'] === 'subscribe' &&
        isset($_GET['hub.verify_token']) && $_GET['hub.verify_token'] === $VERIFY_TOKEN
    ) {
        echo $_GET['hub.challenge'];
        exit;
    }

    echo "Token incorrecto";
    exit;
}

// 2. Recepción de mensajes POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = file_get_contents("php://input");
    file_put_contents("log.txt", $data . "\n", FILE_APPEND);

    http_response_code(200);
    echo "EVENT_RECEIVED";
    exit;
}

http_response_code(404);
echo "Not Found";

