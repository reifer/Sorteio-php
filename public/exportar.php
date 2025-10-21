<?php
$db = new SQLite3(__DIR__ . '/../db/sorteio.db');

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=participantes.csv');

// Adiciona BOM para compatibilidade com Excel
echo "\xEF\xBB\xBF";

$output = fopen('php://output', 'w');

// Cabeçalho
fputcsv($output, ['ID', 'Nome', 'Telefone', 'Cotas', 'Valor', 'Status', 'Data']);

$result = $db->query("SELECT * FROM participantes ORDER BY criado_em DESC");

while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $dataFormatada = date('d/m/Y H:i', strtotime($row['criado_em']));
    fputcsv($output, [
        $row['id'],
        $row['nome'],
        $row['telefone'],
        $row['qtd'],
        number_format($row['valor'], 2, ',', '.'),
        $row['status'],
        $dataFormatada
    ]);
}

fclose($output);