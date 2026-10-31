<?php

require_once "../database/conexao.php";

$sql = "
SELECT
    profissionais.id,
    profissionais.nome,
    especialidade.nome AS especialidade
FROM profissionais
INNER JOIN especialidade
ON especialidade.id = profissionais.especialidade_id
";

$stmt = $conn->query($sql);

echo json_encode(
    $stmt->fetchAll(PDO::FETCH_ASSOC)
);