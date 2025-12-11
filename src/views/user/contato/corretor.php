<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/contato.css">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <script src="https://kit.fontawesome.com/412e60f1e0.js" crossorigin="anonymous"></script>
    <title>Falar com Corretor<?php echo isset($corretor['nome']) ? ' - ' . htmlspecialchars($corretor['nome']) : ''; ?></title>
</head>
<body>
    <?php include VIEWS . 'includes/alert.php';?>
    <?php include VIEWS . 'includes/navbar.php';?>
    <main class="contact-page">
        <section class="contact-section">
            <div class="titulo">
                <h1>Fale com o Corretor<?php echo isset($corretor['nome']) ? ' - ' . htmlspecialchars($corretor['nome']) : ''; ?></h1>
                <p>
                    Interessado no imóvel <b><?php echo htmlspecialchars($imovel['nome'] ?? 'Selecionado'); ?></b>? 
                    Envie uma mensagem diretamente para o responsável.
                </p>
            </div>

            <form method="POST" class="contact-form">
                <input type="hidden" name="imovel_id" value="<?php echo htmlspecialchars($imovel['id'] ?? ''); ?>">
                <input type="hidden" name="corretor_id" value="<?php echo htmlspecialchars($corretor['id'] ?? ''); ?>">

                <div class="form-control">
                    <label for="nome">Seu Nome</label>
                    <input type="text" id="nome" name="nome" required value="<?php echo htmlspecialchars($remetente_nome ?? ''); ?>"<?php echo isset($remetente_nome) ? 'disabled' : ''; ?>>
                </div>

                <div class="form-control">
                    <label for="telefone">Telefone (Whatsapp)</label>
                    <input type="tel" id="telefone" name="telefone" placeholder="(99) 99999-9999" required value="<?php echo htmlspecialchars($remetente_telefone ?? ''); ?>"<?php echo isset($remetente_telefone) ? 'disabled' : ''; ?>>
                </div>

                <div class="form-control">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($remetente_email ?? ''); ?>"<?php echo isset($remetente_email) ? 'disabled' : ''; ?>>
                </div>
                <div class="form-control">
                    <label for="assunto">Assunto</label>
                    <input type="text" id="assunto" name="assunto" required>
                </div>
                <div class="form-control">
                    <label for="mensagem">Mensagem (Ex: "Gostaria de agendar uma visita")</label>
                    <textarea id="mensagem" name="mensagem" rows="6" required>Tenho interesse no imóvel e gostaria de mais informações.</textarea>
                </div>
                <button type="submit" class="submit-button">Solicitar Contato</button>
            </form>
        </section>
    </main>
    <?php include VIEWS . 'includes/footer.php';?>
</body>
</html>