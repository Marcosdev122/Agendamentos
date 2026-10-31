<?php

require_once "../database/conexao.php";

$cpf = $_POST['cpf'];
$email = $_POST['email'];
$senha = $_POST['senha'];
$confirmar = $_POST['confirmar'];

if ($senha != $confirmar) {
    echo "As senhas não coincidem.";
    exit;
}

$sql = "SELECT id
        FROM users
        WHERE cpf = :cpf
        AND email = :email";

$stmt = $conn->prepare($sql);
$stmt->bindValue(":cpf", $cpf);
$stmt->bindValue(":email", $email);
$stmt->execute();

if ($stmt->rowCount() == 0) {

    echo "CPF ou e-mail esta errado.";
    exit;

}

$hash = password_hash($senha, PASSWORD_DEFAULT);

$sql = "UPDATE users
        SET senha = :senha
        WHERE cpf = :cpf
        AND email = :email";

$stmt = $conn->prepare($sql);

$stmt->bindValue(":senha", $hash);
$stmt->bindValue(":cpf", $cpf);
$stmt->bindValue(":email", $email);

if ($stmt->execute()) {

    echo "success";

} else {

    echo "Erro ao alterar a senha.";

}