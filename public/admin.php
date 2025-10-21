<?php
$db = new SQLite3(__DIR__ . '/../db/sorteio.db');

// Atualiza status se solicitado
$mensagem = '';
if (isset($_GET['confirmar'])) {
    $id = (int) $_GET['confirmar'];

    // Verifica se o ID existe com segurança
    $stmtCheck = $db->prepare("SELECT COUNT(*) as total FROM participantes WHERE id = :id");
    $stmtCheck->bindValue(':id', $id, SQLITE3_INTEGER);
    $resCheck = $stmtCheck->execute();
    $check = $resCheck->fetchArray(SQLITE3_ASSOC)['total'] ?? 0;

    if ($check > 0) {
        $stmt = $db->prepare("UPDATE participantes SET status = 'confirmado' WHERE id = :id");
        if ($stmt) {
            $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
            $success = $stmt->execute();
            if ($success) {
                header("Location: admin.php?status=ok");
                exit;
            } else {
                $mensagem = "Erro ao confirmar participante ID $id.";
            }
        } else {
            $mensagem = "Erro ao preparar atualização para ID $id.";
        }
    } else {
        $mensagem = "Participante ID $id não encontrado.";
    }
}

// Consulta todos os participantes
$result = $db->query("SELECT * FROM participantes ORDER BY criado_em DESC");

// Estatísticas
$stats = $db->querySingle("SELECT COUNT(*) FROM participantes");
$total = $db->querySingle("SELECT SUM(valor) FROM participantes WHERE status = 'confirmado'");
$total = $total ?: 0;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Painel Administrativo</title>
  <style>
    body { font-family: Arial, sans-serif; padding: 20px; background: #f4f4f4; }
    h1 { color: #004080; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; }
    th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
    th { background-color: #004080; color: white; }
    .btn { padding: 6px 12px; background: #008000; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; }
    .btn:hover { background: #006400; }
    .stats { margin-top: 10px; font-weight: bold; }
    .mensagem { margin-top: 10px; padding: 10px; background: #ffefc4; border: 1px solid #e0c060; border-radius: 5px; color: #333; }
  </style>
</head>
<body>
  <h1>📋 Painel Administrativo</h1>

  <div class="stats">
    Total de participantes: <?= $stats ?><br>
    Total arrecadado (confirmado): R$ <?= number_format($total, 2, ',', '.') ?>
  </div>

  <?php if (isset($_GET['status']) && $_GET['status'] === 'ok'): ?>
    <div class="mensagem">✅ Participante confirmado com sucesso!</div>
  <?php elseif ($mensagem): ?>
    <div class="mensagem">⚠️ <?= htmlspecialchars($mensagem) ?></div>
  <?php endif; ?>

  <table>
    <tr>
      <th>ID</th>
      <th>Nome</th>
      <th>Telefone</th>
      <th>Cotas</th>
      <th>Valor</th>
      <th>Status</th>
      <th>Data</th>
      <th>Ações</th>
    </tr>
    <?php while ($row = $result->fetchArray(SQLITE3_ASSOC)): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['nome']) ?></td>
        <td><?= htmlspecialchars($row['telefone']) ?></td>
        <td><?= $row['qtd'] ?></td>
        <td>R$ <?= number_format($row['valor'], 2, ',', '.') ?></td>
        <td><?= htmlspecialchars($row['status']) ?></td>
        <td>
          <?php
            $data = strtotime($row['criado_em']);
            echo date('d/m/Y H:i', $data);
          ?>
        </td>
        <td>
          <?php if ($row['status'] === 'pendente'): ?>
            <a href="?confirmar=<?= $row['id'] ?>" class="btn">Confirmar</a>
          <?php else: ?>
            ✅
          <?php endif; ?>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>
</body>
</html>