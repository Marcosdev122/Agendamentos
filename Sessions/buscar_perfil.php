<?php

session_start();

require_once "../database/conexao.php";

$user_id = $_SESSION['user_id'];

$sql = "
SELECT
    nome,
    cpf,
    email,
    sexo
FROM users
WHERE id = :id
";

$stmt = $conn->prepare($sql);

$stmt->bindValue(":id", $user_id);

$stmt->execute();

echo json_encode(
    $stmt->fetch(PDO::FETCH_ASSOC)
);