<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <script src="https://kit.fontawesome.com/412e60f1e0.js" crossorigin="anonymous"></script>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/auth.css">
    <title>Urbanline Imóveis - Login</title>
</head>
<body>
    <section class="auth">
        <div id="welcome-content">
            <img src="assets/img/icones/logo_sem_fundo2.png" class="logo-icon" alt="Logo Urbanline Imóveis">
            <div>
                <h1>Esqueceu a senha?</h1>
                <p>Esqueceu a senha? Sem problemas! Insira seu email para receber um link de verificação para trocar de senha!</p>
            </div>
        </div>
        <div class="form-auth-content">
            <form action="../src/processos_antigos/envia-email.php" method="post" id="auth-form">
                <h2>Urbanline Imóveis</h2>
                <div class="input-wrapper">
                    <i class="fa-solid fa-envelope"></i>
                    <label for="email" class="sr-only">Email:</label>
                    <input type="email" name="email" id="email" autocomplete="email" required placeholder="Digite seu email">
                </div>
                <button type="submit" class="auth-submit-btn">Entrar</button>
                <p>Não possui cadastro? <a href="cadastro.php">Cadastre-se</a></p>
            </form>
        </div>
    </section>
</body>
</html>