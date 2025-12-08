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
            <li class="navbar-link"><a href="/"><i class="fa-solid fa-house"></i> Início</a></li>
            <li class="navbar-link"><a href="/search"><i class="fa-solid fa-magnifying-glass"></i> Procurar Imóvel</a></li>
            <li class="navbar-link"><a href="/contato"><i class="fa-solid fa-envelope"></i> Contato</a></li>
            <?php

            if (isset($_SESSION['logado']) && $_SESSION['logado'] === TRUE) {
                $defaultPicture = "assets/img/icones/default-user.jpg";
                
                $pictureUrl = $_SESSION['user_picture'] ?? $defaultPicture;
                
                $totalMsg = isset($totalMensagensNaoLidas) ? (int)$totalMensagensNaoLidas : 0;

                $totalMsg = 21;
                $contadorFormatado = '';

                if ($totalMsg > 0) {
                    $contadorFormatado = $totalMsg > 99 ? '99+' : $totalMsg;
                    $badgeStyle = ''; // Estilo para aparecer
                } else {
                    $contadorFormatado = '0';
                    $badgeStyle = 'display: none;'; // Estilo para ocultar
                }

                echo '
                    <li class="navbar-link navbar-mensagens-link">
                        <a href="/mensagens">
                            <i class="fa-solid fa-comment"></i> Mensagens
                            <span class="circle-notification" style="' . $badgeStyle . '">' . $contadorFormatado . '</span>
                        </a>
                    </li>    
                    ';
                echo '<li class="navbar-link" id="navbar-logout"><a href="/logout"><i class="fa-solid fa-right-from-bracket"></i> Sair da conta</a></li>';
                
                echo '<img src="' . htmlspecialchars($pictureUrl) . '" style="border-radius: 50%; width: 36px; height: 36px; object-fit: cover;" alt="Foto de Perfil do Usuário">';
            } else {
                echo '<li class="navbar-link"><a href="/cadastro">Cadastro</a></li>';
                echo '<li class="navbar-link"><a href="/login">Entrar</a></li>';
            }
            ?>
        </ul>
    </nav>
</header>