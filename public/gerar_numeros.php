<?php
$db = new SQLite3(__DIR__ . '/../db/sorteio.db');

// 🔢 Buscar todos os números já usados
$usados = [];
$result = $db->query("SELECT numero FROM numeros_sorte");
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $usados[] = (int)$row['numero'];
}

// 🔍 Buscar participantes confirmados sem números atribuídos
$participantes = $db->query("
    SELECT id, qtd 
    FROM participantes 
    WHERE status = 'confirmado' 
    AND id NOT IN (SELECT participante_id FROM numeros_sorte) 
    ORDER BY criado_em ASC
");

$limite = 76000;
$disponiveis = array_diff(range(1, $limite), $usados);
shuffle($disponiveis); // embaralha os disponíveis

$pos = 0;
$totalGerados = 0;
$erros = [];

while ($row = $participantes->fetchArray(SQLITE3_ASSOC)) {
    $pid = (int)$row['id'];
    $qtd = (int)$row['qtd'];

    for ($i = 0; $i < $qtd; $i++) {
        if (!isset($disponiveis[$pos])) {
            $erros[] = "⚠️ Limite de 76.000 números atingido! Nenhum número disponível para o participante ID $pid.";
            break 2;
        }

        $numero = $disponiveis[$pos];
        $stmt = $db->prepare("INSERT INTO numeros_sorte (participante_id, numero) VALUES (:pid, :num)");
        if (!$stmt) {
            $erros[] = "Erro ao preparar statement para participante ID $pid.";
            continue;
        }

        $stmt->bindValue(':pid', $pid, SQLITE3_INTEGER);
        $stmt->bindValue(':num', $numero, SQLITE3_INTEGER);
        $success = $stmt->execute();

        if (!$success) {
            $erros[] = "Erro ao inserir número $numero para participante ID $pid.";
            continue;
        }

        $pos++;
        $totalGerados++;
    }
}

// 🧾 Resultado
echo "<h2>✅ $totalGerados números aleatórios gerados com sucesso!</h2>";
if (!empty($erros)) {
    echo "<h3>⚠️ Ocorreram alguns problemas:</h3><ul>";
    foreach ($erros as $erro) {
        echo "<li>$erro</li>";
    }
    echo "</ul>";
}