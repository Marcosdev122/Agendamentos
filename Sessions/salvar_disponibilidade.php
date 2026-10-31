<?php

require_once "../database/conexao.php";

$profissional_id = $_POST['profissional_id'] ?? '';
$data_inicio = $_POST['data_inicio'] ?? '';
$data_fim = $_POST['data_fim'] ?? '';
$dias = $_POST['dias'] ?? [];

$horario = $_POST['horario'] ?? '';
$vagas = $_POST['vagas'] ?? '';

if (
    empty($profissional_id) ||
    empty($data_inicio) ||
    empty($data_fim) ||
    empty($dias) ||
    empty($horario) ||
    empty($vagas)
) {

    echo json_encode([
        "success" => false,
        "message" => "Preencha todos os campos."
    ]);

    exit;
}

if ($data_inicio < date('Y-m-d')) {

    echo json_encode([
        "success" => false,
        "message" => "Não é possível cadastrar datas anteriores."
    ]);

    exit;
}

if ($data_fim < $data_inicio) {

    echo json_encode([
        "success" => false,
        "message" => "A data final não pode ser menor que a data inicial."
    ]);

    exit;
}

$dataAtual = new DateTime($data_inicio);
$dataFinal = new DateTime($data_fim);

$totalInseridos = 0;
while ($dataAtual <= $dataFinal) {

    $diaSemana = $dataAtual->format('w');

    if (in_array($diaSemana, $dias)) {

        $data = $dataAtual->format('Y-m-d');


        $sql = "
               SELECT id
               FROM disponibilidade
               WHERE profissional_id = :profissional_id
               AND data = :data
               AND horario = :horario
               ";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':profissional_id', $profissional_id);
        $stmt->bindValue(':data', $data);
        $stmt->bindValue(':horario', $horario);

        $stmt->execute();


        $sql = "
           SELECT horario
           FROM disponibilidade
           WHERE profissional_id = :profissional_id
            AND data = :data
               ";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':profissional_id', $profissional_id);
        $stmt->bindValue(':data', $data);

        $stmt->execute();

        $novoHorario = strtotime($horario);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $horarioExistente = strtotime($row['horario']);

            $diferenca = abs(
                ($novoHorario - $horarioExistente) / 60
            );

            if ($diferenca < 90) {

                echo json_encode([
                    "success" => false,
                    "message" => "Existe um horário com menos de 1h30 de intervalo em " . date('d/m/Y', strtotime($data))
                ]);

                exit;
            }
            $sqlCount = "SELECT COALESCE(SUM(vagas), 0) as total_vagas
             FROM disponibilidade
             WHERE profissional_id = :profissional_id
               AND data = :data";
            $stmtCount = $conn->prepare($sqlCount);
            $stmtCount->bindValue(':profissional_id', $profissional_id);
            $stmtCount->bindValue(':data', $data);
            $stmtCount->execute();
            $totalVagas = $stmtCount->fetch(PDO::FETCH_ASSOC)['total_vagas'];

            // Se a soma das vagas existentes + novas vagas > 3, bloqueia
            if ($totalVagas + $vagas > 3) {
                echo json_encode([
                    "success" => false,
                    "message" => "Limite de 3 vagas por dia atingido para este profissional em " . date('d/m/Y', strtotime($data)) . "."
                ]);
                exit;
            }
        }
        $sql = "
        INSERT INTO disponibilidade
        (
            profissional_id,
            data,
            horario,
            vagas
        )
        VALUES
        (
            :profissional_id,
            :data,
            :horario,
            :vagas
        )
        ";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':profissional_id', $profissional_id);
        $stmt->bindValue(':data', $data);
        $stmt->bindValue(':horario', $horario);
        $stmt->bindValue(':vagas', $vagas);

        $stmt->execute();
        $totalInseridos++;
    }

    $dataAtual->modify('+1 day');
}

if ($totalInseridos === 0) {
    echo json_encode([
        "success" => false,
        "message" => "Nenhuma disponibilidade foi cadastrada. Verifique os dias selecionados."
    ]);
    exit;
}

echo json_encode([
    "success" => true,
    "message" => $totalInseridos . " disponibilidades cadastradas com sucesso."
]);
