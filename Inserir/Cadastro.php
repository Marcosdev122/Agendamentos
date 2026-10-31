<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>


    <form id="cadastro">
        <h1>Cadastro</h1>
        <label for="name">Nome completo</label>
        <input type="text" id="name" name="name" required>

        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" required autocomplete="email">

        <label for="cpf">CPF</label>
        <input type="text" id="cpf" name="cpf" required>
        <div class="campo">
            <label>Sexo</label>

            <div class="sexo-icons">
                <div class="sexo">
                    <label>
                        <input type="radio" name="sexo" value="Masculino">
                        <i class="fa-solid fa-person"></i>
                        <span>Masculino</span>
                    </label>
                </div>
                <div class="sexo">
                    <label>
                        <input type="radio" name="sexo" value="Feminino">
                        <i class="fa-solid fa-person-dress"></i>
                        <span>Feminino</span>
                    </label>
                </div>
            </div>
        </div>

        <label for="password">Senha</label>
        <input type="password" id="password" name="password" required>
        <div class="campo">

            <label for="confirm_password">Confirmação de senha</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <button type="button" onclick="cadastrar()">Cadastrar</button>
            <p>Já possui uma conta?
                <a href="../index.php">Entrar</a>
            </p>
    </form>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="../js/main.js"></script>
</body>

</html>