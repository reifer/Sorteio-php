<?php
// Ativar exibição de erros para debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inicia buffer para evitar saída fora do JSON
ob_start();

// Forçar retorno JSON
header('Content-Type: application/json; charset=utf-8');

// ⚙️ Access Token
$access_token = getenv('MP_ACCESS_TOKEN');
if (!$access_token) {
    $access_token = 'APP_USR-2221051077100454-101711-2e56ad1a374acc9fcb5370beb067fe35-169840778'; // teste
}

// 📥 Receber dados JSON
$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

// Validação básica
if (!$data || !isset($data['nome'], $data['telefone'], $data['qtd'], $data['valor'])) {
    ob_end_clean();
    echo json_encode(['error' => 'Dados inválidos ou incompletos', 'raw' => $raw]);
    exit;
}

// 🧼 Sanitização
$nome = htmlspecialchars(trim($data['nome']), ENT_QUOTES, 'UTF-8');
$telefone = preg_replace('/\D/', '', $data['telefone']);
$qtd = max(1, (int)$data['qtd']);
$valor = max(1, floatval($data['valor']));

// 🔗 URLs de retorno
$back_urls = [
    "success" => "https://sorteio.iprsm.com.br/pagamento_concluido.php?telefone=$telefone",
    "failure" => "https://sorteio.iprsm.com.br/pagamento_aguardando.html",
    "pending" => "https://sorteio.iprsm.com.br/pagamento_aguardando.html"
];

// 🧾 Corpo da requisição
$body = [
    "items" => [[
        "title" => "Cota Sorteio IPRSM ({$qtd} números)",
        "quantity" => 1,
        "unit_price" => $valor,
        "currency_id" => "BRL"
    ]],
    "payer" => [
        "name" => $nome,
        "phone" => [
            "area_code" => substr($telefone, 0, 2),
            "number" => substr($telefone, 2)
        ]
    ],
    "back_urls" => $back_urls,
    "auto_return" => "approved",
    "binary_mode" => true
];

// 🔄 Requisição para Mercado Pago
$ch = curl_init("https://api.mercadopago.com/checkout/preferences");
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "Authorization: Bearer $access_token"
    ],
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($body)
]);

$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_error = curl_error($ch);
curl_close($ch);

// Erro de conexão
if ($curl_error) {
    ob_end_clean();
    echo json_encode(['error' => 'Erro de conexão com Mercado Pago', 'details' => $curl_error]);
    exit;
}

// Validação da resposta
$result = json_decode($response, true);
if ($httpcode !== 201 || !$result || !isset($result['init_point'], $result['id'])) {
    ob_end_clean();
    echo json_encode([
        'error' => 'Resposta inválida do Mercado Pago',
        'http_code' => $httpcode,
        'raw' => $response
    ]);
    exit;
}

$init_point = $result['init_point'];
$preference_id = $result['id'];

// 🧠 Salvar no banco
try {
    $db = new SQLite3(__DIR__ . '/../db/sorteio.db');

    $stmt = $db->prepare("INSERT INTO participantes (nome, telefone, qtd, valor, status, preference_id) VALUES (:nome, :telefone, :qtd, :valor, 'pendente', :pid)");
    if (!$stmt) {
        throw new Exception("Erro ao preparar statement");
    }

    $stmt->bindValue(':nome', $nome, SQLITE3_TEXT);
    $stmt->bindValue(':telefone', $telefone, SQLITE3_TEXT);
    $stmt->bindValue(':qtd', $qtd, SQLITE3_INTEGER);
    $stmt->bindValue(':valor', $valor, SQLITE3_FLOAT);
    $stmt->bindValue(':pid', $preference_id, SQLITE3_TEXT);

    $success = $stmt->execute();
    if (!$success) {
        throw new Exception("Erro ao executar statement");
    }

    ob_end_clean();
    echo json_encode([
        'init_point' => $init_point,
        'preference_id' => $preference_id
    ]);
} catch (Exception $e) {
    ob_end_clean();
    echo json_encode(['error' => 'Erro ao salvar no banco', 'details' => $e->getMessage()]);
}