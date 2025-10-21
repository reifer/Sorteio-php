<?php
session_start();
if (!isset($_SESSION['dados_usuario'])) {
  header('Location: index.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Termos do Sorteio</title>
</head>
<body>
  <h2>Termos do Sorteio</h2>
  <p>Ao participar, você concorda com todas as regras estabelecidas pela organização do sorteio.</p>

  <form action="../src/controllers/PaymentController.php" method="POST">
    <label>
      <input type="checkbox" name="aceito" required>
      Li e aceito os termos
    </label><br><br>
    <button type="submit">Ir para pagamento</button>
  </form>
</body>
</html>