<?php
$db = new SQLite3(__DIR__ . '/../db/sorteio.db');

$telefone = preg_replace('/\D/', '', $_GET['telefone'] ?? '');
if (!$telefone) {
  echo "<h2>Telefone não informado.</h2>";
  exit;
}

$stmt = $db->prepare("SELECT id, nome, valor, qtd FROM participantes WHERE telefone = :telefone AND status = 'confirmado' ORDER BY criado_em DESC LIMIT 1");
$stmt->bindValue(':telefone', $telefone, SQLITE3_TEXT);
$participante = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

if (!$participante) {
  echo "<h2>Participante não encontrado ou pagamento não confirmado.</h2>";
  exit;
}

$numeros = [];
$stmt = $db->prepare("SELECT numero FROM numeros_sorte WHERE participante_id = :pid ORDER BY numero ASC");
$stmt->bindValue(':pid', $participante['id'], SQLITE3_INTEGER);
$result = $stmt->execute();
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
  $numeros[] = str_pad($row['numero'], 5, '0', STR_PAD_LEFT);
}

$mensagem = "Olá {$participante['nome']}! 🎉 Seu pagamento foi confirmado.\n\nVocê recebeu os seguintes números da rifa:\n" . implode(", ", $numeros) . "\n\nValor: R$ " . number_format($participante['valor'], 2, ',', '.') . "\n\nBoa sorte no sorteio! 🍀";
$link = "https://wa.me/55{$telefone}?text=" . urlencode($mensagem);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pagamento Concluído - IPR São Miguel</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      background: linear-gradient(to bottom right, #fffbea, #f9f9f9);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      text-align: center;
      font-family: 'Poppins', sans-serif;
    }

    .sucesso {
      background: #fff;
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 0 20px rgba(0,0,0,0.1);
      max-width: 400px;
      animation: fadeIn 0.6s ease;
    }

    h2 {
      color: #27ae60;
      margin-bottom: 10px;
    }

    .numeros {
      margin: 20px 0;
      font-size: 1.2em;
      color: #e67e22;
      font-weight: 600;
    }

    button {
      background: #f39c12;
      color: white;
      border: none;
      padding: 12px 25px;
      border-radius: 25px;
      font-size: 1em;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    button:hover {
      background: #d35400;
      transform: scale(1.05);
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: scale(0.9); }
      to { opacity: 1; transform: scale(1); }
    }
  </style>
</head>
<body>
  <div class="sucesso">
    <h2>🎉 Pagamento realizado com sucesso!</h2>
    <p>Olá <strong><?= htmlspecialchars($participante['nome']) ?></strong>, seus números da rifa foram sorteados automaticamente:</p>
    <div class="numeros"><?= implode(', ', $numeros) ?></div>
    <p>Você também receberá uma mensagem no WhatsApp com os detalhes.</p>
    <button onclick="voltarInicio()">Voltar à Página Principal</button>
  </div>

  <script>
    function voltarInicio() {
      window.location.href = "index.php";
    }

    window.open("<?= $link ?>", "_blank", "noopener");
  </script>
</body>
</html>