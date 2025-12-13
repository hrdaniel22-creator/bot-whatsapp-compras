<?php

$VERIFY_TOKEN = "mibot2025";

/**
 * =========================
 * VERIFICACIÓN DE WEBHOOK
 * =========================
 */
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $mode  = $_GET['hub_mode'] ?? '';
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

/**
 * =========================
 * RECEPCIÓN DE MENSAJES
 * =========================
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $input = file_get_contents("php://input");

    // Guarda logs para depuración
    file_put_contents(__DIR__ . "/log.txt", $input . PHP_EOL, FILE_APPEND);

    http_response_code(200);
    echo "EVENT_RECEIVED";
    exit;
}

http_response_code(404);
echo "Not Found";









