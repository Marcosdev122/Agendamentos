<?php

session_start();

require_once "../database/conexao.php";

$user_id = $_SESSION['user_id'];

$sql = "
SELECT
    name,
    email,
    cpf,
    sexo
FROM users
WHERE id = :id
";

$stmt = $conn->prepare($sql);
$stmt->bindValue(":id", $user_id);
$stmt->execute();

$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

?>
<link rel="stylesheet" href="../assets/perfil.css">
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<div class="container-perfil">

    <div class="card-perfil">

        <div class="avatar">
            <i class="fa-solid fa-user"></i>
        </div>

        <h2>Meu Perfil</h2>
        <div class="info">
            <label><i class="fa-solid fa-user"></i> Nome</label>
            <span><?= $usuario['name']; ?></span>
        </div>

        <div class="info">
            <label><i class="fa-solid fa-envelope"></i> E-mail</label>
            <span><?= $usuario['email']; ?></span>
        </div>

        <div class="info">
            <label><i class="fa-solid fa-id-card"></i> CPF</label>
            <span><?= $usuario['cpf']; ?></span>
        </div>

        <div class="info">
            <label><i class="fa-solid fa-venus-mars"></i> Sexo</label>
            <span><?= $usuario['sexo']; ?></span>
        </div>

        <button
            class="btn-editar"
            onclick="location.href='editar_perfil.php'">

            <i class="fa-solid fa-pen"></i>
            Editar Perfil

        </button>
        <a
            href="../js/principal.php"
            class="btn-voltar">

            <i class="fa-solid fa-arrow-left"></i>

            Voltar

        </a>
    </div>

</div>