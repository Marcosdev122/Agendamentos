<?php

session_start();

require_once "../database/conexao.php";

$user_id = $_SESSION['user_id'];

$nome = trim($_POST['nome']);
$email = trim($_POST['email']);
$sexo = $_POST['sexo'];

$senha = $_POST['senha']?? "";
$confirmar = $_POST['confirmar_senha']?? "";

if(empty($nome) || empty($email) || empty($sexo)){

    echo json_encode([
        "success" => false,
        "message" => "Preencha todos os campos."
    ]);

    exit;
}

if(!filter_var($email, FILTER_VALIDATE_EMAIL)){

    echo json_encode([
        "success" => false,
        "message" => "E-mail inválido."
    ]);

    exit;
}

if(!empty($senha)){

    if($senha != $confirmar){

        echo json_encode([
            "success" => false,
            "message" => "As senhas não conferem."
        ]);

        exit;
    }

    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    $sql = "
    UPDATE users
    SET
        name = :name,
        email = :email,
        sexo = :sexo,
        senha = :senha
    WHERE id = :id
    ";

    $stmt = $conn->prepare($sql);

    $stmt->bindValue(":name",$nome);
    $stmt->bindValue(":email",$email);
    $stmt->bindValue(":sexo",$sexo);
    $stmt->bindValue(":senha",$senhaHash);
    $stmt->bindValue(":id",$user_id);

}else{

    $sql = "
    UPDATE users
    SET
        name = :name,
        email = :email,
        sexo = :sexo
    WHERE id = :id
    ";

    $stmt = $conn->prepare($sql);

    $stmt->bindValue(":name",$nome);
    $stmt->bindValue(":email",$email);
    $stmt->bindValue(":sexo",$sexo);
    $stmt->bindValue(":id",$user_id);

}

if($stmt->execute()){

    echo json_encode([
        "success" => true,
        "message" => "Perfil atualizado com sucesso."
    ]);

}else{

    echo json_encode([
        "success" => false,
        "message" => "Erro ao atualizar o perfil."
    ]);

}