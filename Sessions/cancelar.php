<?php

session_start();
require_once "../database/conexao.php";

if (!isset($_POST['agendamento_id'])) {
    echo "erro";
    exit;
}

$agendamento_id = $_POST['agendamento_id'];

$sql = "DELETE FROM agendamentos WHERE id = :id";

$stmt = $conn->prepare($sql);
$stmt->bindValue(":id", $agendamento_id);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "erro";
}

