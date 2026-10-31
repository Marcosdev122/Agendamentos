<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Recuperar Senha</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="reset.css">
</head>

<body>

    <div class="container">

        <i class="fa-solid fa-lock icone-lock"></i>

        <h1>Recuperar Senha</h1>

        <p>Informe seu CPF e e-mail para criar uma nova senha.</p>

        <form id="password_reset">

            <div class="campo">
                <label>CPF</label>
                <input type="text" id="cpf" name="cpf" required>
            </div>
            <div class="campo">
                <label>E-mail</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="campo">
                <label>Nova Senha</label>
                <input type="password" id="senha" name="senha" required>
            </div>
            <div class="campo">
                <label>Confirmar Senha</label>
                <input type="password" id="confirmar" name="confirmar" required>
            </div>

            <button type="button" onclick="alterarSenha()">
                Alterar Senha
            </button>

        </form>

        <div class="voltar">
            <a href="../index.php">← Voltar ao Login</a>
        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="../js/main.js"></script>

</body>

</html>