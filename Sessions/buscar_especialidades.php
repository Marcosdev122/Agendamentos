<?php 

require_once "../database/conexao.php";

$sql = "SELECT * FROM especialidades";

$stmt = $conn->prepare($sql);
$stmt->execute();

echo json_encode(
    $stmt->fetchAll(PDO::FETCH_ASSOC)
);