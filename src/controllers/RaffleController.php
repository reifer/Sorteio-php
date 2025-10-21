<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nome = $_POST['nome'];
  $telefone = $_POST['telefone'];
  $quantidade = $_POST['quantidade'];

  session_start();
  $_SESSION['dados_usuario'] = compact('nome', 'telefone', 'quantidade');

  header('Location: ../../public/termos.php');
  exit;
}