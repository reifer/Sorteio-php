<?php
// Caminho absoluto para o banco SQLite
$dbPath = __DIR__ . '/sorteio.db';

// Cria ou abre o banco
$db = new SQLite3($dbPath);

// Cria a tabela de participantes
$db->exec("CREATE TABLE IF NOT EXISTS participantes (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  nome TEXT NOT NULL,
  telefone TEXT NOT NULL,
  qtd INTEGER NOT NULL,
  valor REAL NOT NULL,
  status TEXT DEFAULT 'pendente',
  preference_id TEXT,
  criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
)");

$db->exec("CREATE TABLE IF NOT EXISTS numeros_sorte (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  participante_id INTEGER NOT NULL,
  numero INTEGER NOT NULL,
  FOREIGN KEY (participante_id) REFERENCES participantes(id)
)");


echo "✅ Banco de dados inicializado com sucesso.";