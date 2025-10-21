<?php
// Forçar retorno JSON
header('Content-Type: application/json; charset=utf-8');

// Access Token
$access_token = 'APP_USR-2221051077100454-101711-2e56ad1a374acc9fcb5370beb067fe35-169840778'; // mesmo usado em criar_pagamento.php

// Receber notificação
$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

// Verificar tipo e ID
$type = $data['type'] ?? '';
$id = $data['data']['id'] ?? null;

if ($type !== 'payment' || !$id) {
    http_response_code(400);
    echo json_encode(['error' => 'Notificação inválida', 'received' => $data]);
    exit;
}

// Buscar status do pagamento
$ch = curl_init("https://api.mercadopago.com/v1/payments/$id");
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer $access_token"
    ]
]);
$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_error = curl_error($ch);
curl_close($ch);

// Validar resposta
if ($curl_error || $httpcode < 200 || $httpcode >= 300 || !$response) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Erro ao consultar pagamento',
        'http_code' => $httpcode,
        'curl_error' => $curl_error,
        'raw' => $response
    ]);
    exit;
}

$payment = json_decode($response, true);
$status = $payment['status'] ?? '';
$preference_id = $payment['order']['id'] ?? '';
$external_reference = $payment['external_reference'] ?? '';

// Verifica se o pagamento foi aprovado
if ($status === 'approved' && ($preference_id || $external_reference)) {
    try {
        $db = new SQLite3(__DIR__ . '/../db/sorteio.db');

        $pid = $preference_id ?: $external_reference;
        $stmt = $db->prepare("UPDATE participantes SET status = 'confirmado' WHERE preference_id = :pid");
        if (!$stmt) {
            throw new Exception("Erro ao preparar statement");
        }

        $stmt->bindValue(':pid', $pid, SQLITE3_TEXT);
        $success = $stmt->execute();
        if (!$success) {
            throw new Exception("Erro ao executar statement");
        }

        http_response_code(200);
        echo json_encode(['success' => true, 'status' => $status, 'preference_id' => $pid]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao atualizar banco', 'details' => $e->getMessage()]);
    }
} else {
    http_response_code(200);
    echo json_encode([
        'status' => $status,
        'message' => 'Pagamento não aprovado ou preference_id ausente',
        'payment_id' => $id,
        'preference_id' => $preference_id,
        'external_reference' => $external_reference
    ]);
}