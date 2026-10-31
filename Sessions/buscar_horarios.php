<?php

require_once "../database/conexao.php";

$profissional_id = $_GET['profissional_id'];
$data = $_GET['data'];

$sql = "
SELECT
    horario,
    vagas
FROM disponibilidade
WHERE profissional_id = :profissional_id
AND data = :data
ORDER BY horario
";

$stmt = $conn->prepare($sql);

$stmt->bindValue(':profissional_id', $profissional_id);
$stmt->bindValue(':data', $data);

$stmt->execute();

$horarios = [];

while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

    $sqlAg = "
    SELECT COUNT(*) total
    FROM agendamentos
    WHERE profissional_id = :profissional_id
    AND data = :data
    AND horario = :horario
    ";

    $stmtAg = $conn->prepare($sqlAg);

    $stmtAg->bindValue(':profissional_id', $profissional_id);
    $stmtAg->bindValue(':data', $data);
    $stmtAg->bindValue(':horario', $row['horario']);

    $stmtAg->execute();

    $agendados = $stmtAg->fetch(PDO::FETCH_ASSOC)['total'];

    $vagasRestantes =   $row['vagas'] - $agendados;

    if($vagasRestantes <= 0){
        continue;
    }

    $horarios[] = [

        'horario' => $row['horario'],

        'texto' =>
            $row['horario'] . ' (' .$vagasRestantes .' vagas)'
    ];
}

echo json_encode($horarios);