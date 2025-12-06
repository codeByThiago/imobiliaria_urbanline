<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <script src="https://kit.fontawesome.com/412e60f1e0.js" crossorigin="anonymous"></script>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/auth.css">
    <title>Urbanline Imóveis - Cadastro de Usuário</title>
</head>
<body>
    <section class="auth">
        <div id="welcome-content">
            <img src="assets/img/icones/logo_sem_fundo2.png" class="logo-icon" alt="Logo Urbanline Imóveis">
            <div>
                <p>Seja bem vindo á tela de</p>
                <h1>Registro</h1>
                <p>Encontre o imóvel dos seus sonhos com os melhores preços!</p>
            </div>
        </div>
        <div class="form-auth-content">
            <form action="" method="post" id="auth-form" enctype="multipart/form-data">
                <h2>Urbanline Imóveis</h2>
                <!-- Dados Pessoais -->
                 <div class="progress-bar">
                    <span id="progresso-text">Etapa 1 de 3</span>
                    <div id="barra">
                        <div id="progresso"></div>
                    </div>
                 </div>
                <div class="form-step active" id='step-1'>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-envelope"></i>
                        <label for="nome" class="sr-only">Nome Completo:</label>
                        <input type="text" name="nome" id="nome" autocomplete="name" required placeholder="Digite seu nome completo">
                    </div>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-phone"></i>
                        <label for="telefone" class="sr-only">Telefone:</label>
                        <input type="text" name="telefone" id="telefone" autocomplete="tel" required placeholder="Telefone (DDD + Número)">
                    </div>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-user"></i>
                        <label for="cpf-cnpj" class="sr-only">CPF:</label>
                        <input type="text" name="cpf" id="cpf" autocomplete="off" required placeholder="CPF">
                    </div>
                </div>

                <!-- Endereço -->
                <div class="form-step" id='step-2'>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-envelope"></i>
                        <label for="cep" class="sr-only">CEP:</label>
                        <input type="text" name="cep" id="cep" autocomplete="off" required placeholder="CEP">
                    </div>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-phone"></i>
                        <label for="uf" class="sr-only">UF (Estado):</label>
                        <input type="text" name="uf" id="uf" autocomplete="off" required placeholder="Digite seu UF (Ex: SP, RJ, MS...)">
                    </div>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-user"></i>
                        <label for="cidade" class="sr-only">Cidade:</label>
                        <input type="text" name="cidade" id="cidade" autocomplete="off" required placeholder="Digite sua cidade">
                    </div>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-user"></i>
                        <label for="bairro" class="sr-only">Bairro:</label>
                        <input type="text" name="bairro" id="bairro" autocomplete="off" required placeholder="Digite seu bairro">
                    </div>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-envelope"></i>
                        <label for="logradouro" class="sr-only">Logradouro:</label>
                        <input type="text" name="logradouro" id="logradouro" autocomplete="off" required placeholder="Logradouro (Ex: Avenida Marechal Deodoro, Rua 7 de Setembro...">
                    </div>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-phone"></i>
                        <label for="numero" class="sr-only">Número:</label>
                        <input type="text" name="numero" id="numero" autocomplete="" required placeholder="Digite o número da casa">
                    </div>
                </div>

                <!-- Acesso e Senha -->
                <div class="form-step" id='step-3'>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-envelope"></i>
                        <label for="email" class="sr-only">Email:</label>
                        <input type="email" name="email" id="email" autocomplete="email" required placeholder="Digite seu email">
                    </div>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-key"></i>
                        <label for="senha" class="sr-only">Senha:</label>
                        <input type="password" name="senha" id="senha" autocomplete="new-password" required placeholder="Digite sua senha">
                        <button type="button" class="eye-password-viewer-btn" id="mostra-senha-btn">
                            <i class="fa-solid fa-eye" id='eye-senha'></i>
                        </button>
                    </div>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-key"></i>
                        <label for="confirme-senha" class="sr-only">Confirme a Senha:</label>
                        <input type="password" name="confirme-senha" id="confirme-senha" autocomplete="new-password" required placeholder="Digite sua senha novamente">
                        <button type="button" class="eye-password-viewer-btn" id="mostra-confirme-senha-btn">
                            <i class="fa-solid fa-eye" id='eye-confirme-senha'></i>
                        </button>
                    </div>
                </div>
                <button type="button" class="prev-step-btn">Seção Anterior</button>
                <button type="button" class="next-step-btn">Próxima Etapa</button>
                <button type="submit" class="auth-submit-btn">Cadastrar</button>

            </form>
        </div>
    </section>
    <script type="module" src="assets/js/senha.js"></script>
    <script type="module" src="assets/js/cep.js"></script>
    <script type="module" src="assets/js/telefone.js"></script>
    <script type="module" src="assets/js/cpf.js"></script>
    <script src="assets/js/cadastro.js"></script>
</body>
</html>