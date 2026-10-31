<?php

session_start();
require_once "../database/conexao.php";

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

if ($role == 'admin') {

    $sql = "
SELECT
    agendamentos.id AS agendamento_id,
    profissionais.nome,
    especialidades.especialidade AS especialidade,

    users.name AS paciente,
    users.email,
    users.CPF AS cpf,
    
    agendamentos.data,
    agendamentos.horario

FROM agendamentosm

INNER JOIN profissionais
    ON profissionais.id = agendamentos.profissional_id

INNER JOIN especialidades
    ON especialidades.id = profissionais.especialidade_id

INNER JOIN users
    ON users.id = agendamentos.user_id
";

    $stmt = $conn->query($sql);
} else {

    $sql = "
SELECT
    agendamentos.id AS agendamento_id,
    profissionais.nome,
    especialidades.especialidade AS especialidade,
    agendamentos.data,
    agendamentos.horario
FROM agendamentos

INNER JOIN profissionais
    ON profissionais.id = agendamentos.profissional_id

INNER JOIN especialidades
    ON especialidades.id = profissionais.especialidade_id

WHERE agendamentos.user_id = :user_id
";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':user_id', $user_id);
    $stmt->execute();
}

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
        <td>
    ";

 
    // Todo mund vê cancelar
    $html .= "
        <button
            onclick='cancelar({$row['agendamento_id']})'
            class='btn-cancelar'>
            <i class='fa-solid fa-xmark'></i>
        </button>
        </td>
    </tr>
    ";
}

echo $html;
