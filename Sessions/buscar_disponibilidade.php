<?php

require_once "../database/conexao.php";

$sql = "
SELECT
    d.*,
    p.nome,
    e.especialidade

FROM disponibilidade d

INNER JOIN profissionais p
    ON p.id = d.profissional_id

INNER JOIN especialidades e
    ON e.id = p.especialidade_id

ORDER BY d.data, d.horario
";

$stmt = $conn->prepare($sql);
$stmt->execute();

$html = '';

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $data = date('d/m/Y', strtotime($row['data']));

    $dias = [
        'Sunday' => 'Domingo',
        'Monday' => 'Segunda-feira',
        'Tuesday' => 'Terça-feira',
        'Wednesday' => 'Quarta-feira',
        'Thursday' => 'Quinta-feira',
        'Friday' => 'Sexta-feira',
        'Saturday' => 'Sábado'
    ];

    $diaSemana = $dias[date('l', strtotime($row['data']))];
    $html .= "
    <tr>

        <td>{$row['especialidade']}</td>

        <td>{$row['nome']}</td>

        <td>
        <strong>$data</strong><br>
        <small>$diaSemana</small>
        </td>

        <td>{$row['horario']}</td>

        <td>{$row['vagas']}</td>

        <td>

            <button
               class='btn-excluir'
                onclick='excluirDisponibilidade({$row['id']})'>

                <i class='fa-solid fa-trash'></i>
                Excluir

            </button>

        </td>

    </tr>
    ";
}

echo $html;
