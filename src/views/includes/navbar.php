<header>
    <nav class="navbar" aria-label="Barra de Navegação Principal">
        <div class="menu-responsivo">
            <a href="/">
                <div class="navbar-brand">
                    <img class="logo" src="assets/img/icones/logo_sem_fundo2.png">
                    <h1>Urbanline Imóveis</h1>
                </div>
            </a>
            <div class="navbar-menu">
                <i class="fa-solid fa-bars" id="menu"></i>
            </div>
        </div>
        <ul class="navbar-items">
            <li class="navbar-link"><a href="/">Início</a></li>
            <li class="navbar-link"><a href="/search">Procurar Imóvel</a></li>
            <?php

            if (isset($_SESSION['logado']) && $_SESSION['logado'] === TRUE) {
                $defaultPicture = "assets/img/icones/default-user.jpg";
                
                $pictureUrl = $_SESSION['user_picture'] ?? $defaultPicture;
                
                echo '<li class="navbar-link"><a href="/logout">Sair da conta</a></li>';
                echo '<i class="fa-solid fa-comment" style="font-size: 24px;"></i>';
                
                echo '<img src="' . htmlspecialchars($pictureUrl) . '" style="border-radius: 50%; width: 30px; height: 30px; object-fit: cover;" alt="Foto de Perfil do Usuário">';
            } else {
                echo '<li class="navbar-link"><a href="/cadastro">Cadastrar-se</a></li>';
                echo '<li class="navbar-link"><a href="/login">Entrar</a></li>';
            }
            ?>
        </ul>
    </nav>
</header>