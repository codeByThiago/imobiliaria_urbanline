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
                <p>Seja bem vindo á tela de</p>
                <h1>Login</h1>
                <p>Encontre o imóvel dos seus sonhos com os melhores preços!</p>
            </div>
        </div>
        <div class="form-auth-content">
            <form action="" method="post" id="auth-form">
                <h2>Urbanline Imóveis</h2>
                <div class="input-wrapper">
                    <i class="fa-solid fa-envelope"></i>
                    <label for="email" class="sr-only">Email:</label>
                    <input type="email" name="email" id="email" autocomplete="email" required placeholder="Digite seu email">
                </div>
                <div class="input-wrapper">
                    <i class="fa-solid fa-key"></i>
                    <label for="senha" class="sr-only">Senha:</label>
                    <input type="password" name="senha" id="senha" autocomplete="current-password" required placeholder="Digite sua senha" min="8">
                    <button type="button" class="eye-password-viewer-btn" id="mostra-confirme-senha-btn">
                        <i class="fa-solid fa-eye" id='eye-confirme-senha'></i>
                    </button>
                </div>
                <div class="input-group">
                    <input type="checkbox" name="remind-me" id="remind-me">
                    <label for="remind-me">Lembrar-me neste dispositivo</label>
                </div>
                <button type="submit" class="auth-submit-btn">Entrar</button>
                <a href="/user/google-login" class="google-btn"><i class="fa-brands fa-google"></i> Entrar com Google</a>
                <p>Esqueceu a senha? <a href="esqueceu-senha.php">Clique aqui.</a></p>
                <p>Não possui cadastro? <a href="cadastro.php">Cadastre-se</a></p>
            </form>
        </div>
    </section>
    <script type="module" src="assets/js/senha.js"></script>
    <script>
        document.getElementById('auth-google-btn').addEventListener('click', function() {
            const googleLoginUrl = "<?= $googleAuthUrl ?>";
            const width = 500, height = 600;
            const left = (screen.width / 2) - (width / 2);
            const top = (screen.height / 2) - (height / 2);

            window.open(
                googleLoginUrl,
                "LoginGoogle",
                `width=${width},height=${height},top=${top},left=${left},resizable=no,scrollbars=no,status=no`
            );
        });
    </script>
</body>
</html>