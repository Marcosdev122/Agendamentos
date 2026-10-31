<?php
session_start();
require_once "../database/conexao.php";

$id = $_SESSION['user_id'];

$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id]);

$usuario = $stmt->fetch(PDO::FETCH_ASSOC);
if (
    !isset($_SESSION['user_id'])
) {
    header("Location: ../index.php");
    exit;
}

if ($_SESSION['role'] == 'admin') {
    header("Location: principal.php");
    exit;
}

?>

<!DOCTYPE html>

<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <title>Meu Perfil</title>

    <link rel="stylesheet" href="../assets/editar_perfil.css">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

    <div class="container">

        <div class="card">

            <div class="card-header">

                <h2>

                    <i class="fa-solid fa-user"></i>

                    Meu Perfil

                </h2>

            </div>

            <div class="card-body">

                <form id="formPerfil" autocomplete="off">

                    <div class="campo">

                        <label>Nome</label>

                        <input
                            type="text"
                            id="nome"
                            value="<?= htmlspecialchars($usuario['name']) ?>">
                    </div>

                    <div class="campo">

                        <label>Email</label>

                        <input
                            type="email"
                            id="email"
                            value="<?= htmlspecialchars($usuario['email']) ?>">

                    </div>

                    <div class="campo">

                        <label>Sexo</label>

                        <select id="sexo">
                            <option value="Masculino"
                                <?= $usuario['sexo'] == 'Masculino' ? 'selected' : '' ?>>
                                Masculino
                            </option>

                            <option value="Feminino"
                                <?= $usuario['sexo'] == 'Feminino' ? 'selected' : '' ?>>
                                Feminino
                            </option>
                        </select>
                    </div>

                    
                    <br>

                    <button
                        type="button"
                        class="btn-salvar"
                        onclick="editarPerfil()">

                        <i class="fa-solid fa-floppy-disk"></i>

                        Salvar Alterações

                    </button>

                    <a
                        href="perfil.php"
                        class="btn-voltar">

                        <i class="fa-solid fa-arrow-left"></i>

                        Voltar

                    </a>

                </form>

            </div>

        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="../js/main.js"></script>

</body>

</html>