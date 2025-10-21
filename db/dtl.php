<?php
$dbPath = __DIR__ . '/sorteio.db';

try {
    $db = new SQLite3($dbPath);
    $result = $db->query("SELECT * FROM participantes ORDER BY criado_em DESC");

    echo "<h2>📋 Lista de Participantes</h2>";
    echo "<table border='1' cellpadding='8' cellspacing='0'>";
    echo "<tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Telefone</th>
            <th>Qtd</th>
            <th>Valor</th>
            <th>Status</th>
            <th>Data</th>
          </tr>";

    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['nome']}</td>
                <td>{$row['telefone']}</td>
                <td>{$row['qtd']}</td>
                <td>R$ " . number_format($row['valor'], 2, ',', '.') . "</td>
                <td>{$row['status']}</td>
                <td>{$row['criado_em']}</td>
              </tr>";
    }

    echo "</table>";
} catch (Exception $e) {
    echo "Erro ao acessar o banco: " . $e->getMessage();
}