<?php
session_start();
require_once "../database/conexao.php";

header('Content-Type: application/json');

$email = $_POST['logemail'];
$senha = $_POST['logsenha'];

$sql = "SELECT * FROM users WHERE email = :email";
$stmt = $conn->prepare($sql);
$stmt->bindValue(":email", $email);
$stmt->execute();

$buscar = $stmt->fetch(PDO::FETCH_ASSOC);

if ($buscar && password_verify($senha, $buscar['senha'])) {

    $_SESSION['user_id'] = $buscar['id'];
    $_SESSION['role'] = $buscar['role'];

    echo json_encode([
        "status" => "success",
        "nome" => $buscar['name']
    ]);
    exit;
}

echo json_encode([
    "status" => "error",
    "message" => "Email ou senha incorretos!"
]);
var_dump($buscar);
exit;