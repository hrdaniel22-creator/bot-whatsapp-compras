<?php

$verify_token = "mibot2025";

// ---- VERIFICACIÓN DEL WEBHOOK ----
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $hub_verify_token = $_GET['hub_verify_token'] ?? '';
    $challenge = $_GET['hub_challenge'] ?? '';

    if ($hub_verify_token === $verify_token) {
        echo $challenge;
        exit;
    } else {
        echo "Error: token inválido";
        exit;
    }
}

// ---- RECEPCIÓN DE MENSAJES ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = file_get_contents("php://input");
    file_put_contents("log.txt", $data . PHP_EOL, FILE_APPEND);

    http_response_code(200);
    echo "EVENT_RECEIVED";
    exit;
}

echo "METODO NO SOPORTADO";
?>
