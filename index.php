<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login/login.css">
</head>

<body>

    <div class="login-container">
        <form id="login">
            <h2>Login</h2>
        <input type="email" name="logemail" placeholder="Digite seu email" required>

        <input type="password" name="logsenha" placeholder="Digite sua senha" required>

        <button type="button" id="btnLogin" onclick="login()">Entrar</button>
        <p>Não tem uma conta?
            <a href="inserir/cadastro.php">Cadastra-se</a>
            <a href="reset/password_reset.php">Esqueceu a senha?</a>
        </p>
        </form>

    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="./js/main.js"></script>
</body>

</html>