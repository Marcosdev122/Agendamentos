<?php

require_once "../database/conexao.php";

$especialidade_id = $_GET['especialidade_id'];

$sql = "
SELECT DISTINCT
    p.id,
    p.nome
FROM profissionais p

WHERE p.especialidade_id = :especialidade

AND EXISTS (

    SELECT 1

    FROM disponibilidade d

    WHERE d.profissional_id = p.id

    AND (

        d.vagas >

        (
            SELECT COUNT(*)
            FROM agendamentos a

            WHERE a.profissional_id = d.profissional_id
            AND a.data = d.data
            AND a.horario = d.horario
        )

    )

)
";

$stmt = $conn->prepare($sql);

$stmt->bindValue(':especialidade', $especialidade_id);

$stmt->execute();

echo json_encode(
    $stmt->fetchAll(PDO::FETCH_ASSOC)
);