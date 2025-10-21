<?php
require __DIR__ . '/../config/config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aceito'])) {
  $dados = $_SESSION['dados_usuario'] ?? null;

  if (!$dados) {
    header('Location: ../../public/index.php');
    exit;
  }

  // Em breve: gerar link/QR code do Mercado Pago

  header('Location: ../../public/pagamento_aguardando.php');
  exit;
}