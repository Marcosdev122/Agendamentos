<?php
require_once "../database/conexao.php";

$name = $_POST['name'];
$email = $_POST['email'];
$cpf = $_POST['cpf'];
$sexo = $_POST['sexo'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    echo "email invalido!";
    exit;
}

if ($password !== $confirm_password) {
    echo "As senhas informadas não coincidem";
    exit;
}

// Verifica se já existe email ou CPF
$sqlCheck = "SELECT email, cpf FROM users WHERE email = :email OR cpf = :cpf";
$stmtCheck = $conn->prepare($sqlCheck);
$stmtCheck->bindValue(":email", $email);
$stmtCheck->bindValue(":cpf", $cpf);
$stmtCheck->execute();

if ($stmtCheck->rowCount() > 0) {

    $user = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if ($user['email'] === $email) {
        echo "Este e-mail já está cadastrado";
        exit;
    }

    if ($user['cpf'] === $cpf) {
        echo "Este CPF já está cadastrado";
        exit;
    }
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users(name, email, cpf, sexo,  senha, role)
        VALUES(:name, :email, :cpf, :sexo, :password, :role)";

$stmt = $conn->prepare($sql);
$stmt->bindValue(":name", $name);
$stmt->bindValue(":email", $email);
$stmt->bindValue(":cpf", $cpf);
$stmt->bindValue(":sexo", $sexo);
$stmt->bindValue(":password", $hash);
$stmt->bindValue(":role", "user");

if ($stmt->execute()) {
    echo "success";
} else {
    echo "error";
}