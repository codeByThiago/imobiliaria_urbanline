<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/contato.css">
    <script src="https://kit.fontawesome.com/412e60f1e0.js" crossorigin="anonymous"></script>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <title>Contato - Urbanline Imóveis</title>
</head>
<body>
    <?php include VIEWS . 'includes/alert.php';?>
    <?php include VIEWS . 'includes/navbar.php';?>
    <main class="contact-page">
        <section class="contact-section">
            <div class="titulo">
                <h1>Fale Conosco</h1>
                <p>Para dúvidas gerais, sugestões ou parcerias, envie sua mensagem abaixo.</p>
            </div>

            <form action="/submit-contact" method="POST" class="contact-form">
                <div class="form-control">
                    <label for="nome">Nome Completo</label>
                    <input type="text" id="nome" name="nome" required>
                </div>
                <div class="form-control">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-control">
                    <label for="assunto">Assunto</label>
                    <select id="assunto" name="assunto">
                        <option value="duvidas">Dúvidas Gerais</option>
                        <option value="parceria">Proposta de Parceria</option>
                        <option value="suporte">Suporte Técnico</option>
                        <option value="outros">Outros</option>
                    </select>
                </div>
                <div class="form-control">
                    <label for="mensagem">Mensagem</label>
                    <textarea id="mensagem" name="mensagem" rows="6" required></textarea>
                </div>
                <button type="submit" class="submit-button">Enviar Mensagem</button>
            </form>
        </section>
    </main>
    <?php include VIEWS . 'includes/footer.php';?>
</body>
</html>