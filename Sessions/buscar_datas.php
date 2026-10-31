<?php

require_once "../database/conexao.php";

$profissional_id = $_GET['profissional_id'];

$sql = "
SELECT DISTINCT d.data

FROM disponibilidade d

WHERE d.profissional_id = :profissional_id

ORDER BY d.data
";

$stmt = $conn->prepare($sql);
$stmt->bindValue(":profissional_id", $profissional_id);
$stmt->execute();

$datas = [];

$dias = [
    'Sunday' => 'Domingo',
    'Monday' => 'Segunda-feira',
    'Tuesday' => 'Terça-feira',
    'Wednesday' => 'Quarta-feira',
    'Thursday' => 'Quinta-feira',
    'Friday' => 'Sexta-feira',
    'Saturday' => 'Sábado'
];

while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

    $sqlVagas = "
    SELECT
        SUM(vagas) as total_vagas
    FROM disponibilidade
    WHERE profissional_id = :profissional_id
    AND data = :data
    ";

    $stmtVagas = $conn->prepare($sqlVagas);
    $stmtVagas->bindValue(':profissional_id', $profissional_id);
    $stmtVagas->bindValue(':data', $row['data']);
    $stmtVagas->execute();

    $totalVagas = $stmtVagas->fetch(PDO::FETCH_ASSOC)['total_vagas'];

    $sqlAgendados = "
    SELECT COUNT(*) as total
    FROM agendamentos
    WHERE profissional_id = :profissional_id
    AND data = :data
    ";

    $stmtAgendados = $conn->prepare($sqlAgendados);
    $stmtAgendados->bindValue(':profissional_id', $profissional_id);
    $stmtAgendados->bindValue(':data', $row['data']);
    $stmtAgendados->execute();

    $agendados = $stmtAgendados->fetch(PDO::FETCH_ASSOC)['total'];

    $vagasRestantes = $totalVagas - $agendados;

    if($vagasRestantes <= 0){
        continue;
    }

    $dataFormatada =
        date('d/m/Y', strtotime($row['data']));

    $diaSemana =
        $dias[
            date('l', strtotime($row['data']))
        ];

    $datas[] = [
        'data' => $row['data'],
        'texto' => $dataFormatada . ' - ' . $diaSemana . ' (' . $vagasRestantes . ' vagas)'
    ];

}

echo json_encode($datas);