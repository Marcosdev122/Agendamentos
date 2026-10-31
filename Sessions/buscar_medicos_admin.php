<?php

require_once "../database/conexao.php";

$especialidade_id = $_GET['especialidade_id'];

$sql = "
SELECT *
FROM profissionais

WHERE especialidade_id = :especialidade_id

ORDER BY nome
";

$stmt = $conn->prepare($sql);

$stmt->bindValue(
    ':especialidade_id',
    $especialidade_id
);

$stmt->execute();

echo json_encode(
    $stmt->fetchAll(PDO::FETCH_ASSOC)
);