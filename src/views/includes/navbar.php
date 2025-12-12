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

            // Verifica se o usuário está logado
            if (isset($_SESSION['logado']) && $_SESSION['logado'] === TRUE) {
                
                // Variáveis Padrão
                $defaultPicture = "assets/img/icones/default-user.jpg";
                $pictureUrl = $_SESSION['user_picture'] ?? $defaultPicture;
                
                // Variável Injetada pelo Controller (Solução 1)
                // Se a variável não for definida (ex: em views sem login), usa 0
                $totalMsg = (int)($totalMensagensNaoLidas ?? 0); 

                $contadorFormatado = '';
                $badgeStyle = 'display: none;'; // Padrão: Oculto

                if ($totalMsg > 0) {
                    $contadorFormatado = $totalMsg > 99 ? '99+' : $totalMsg;
                    $badgeStyle = ''; // Exibe se houver mensagens
                }

                // Links do Usuário Logado
                echo '
                    <li class="navbar-link navbar-mensagens-link">
                        <a href="/mensagens" aria-label="Ver Mensagens">
                            <i class="fa-solid fa-comment"></i> Mensagens
                            <span class="circle-notification" style="' . $badgeStyle . '">' . $contadorFormatado . '</span>
                        </a>
                    </li>    
                    <li class="navbar-link" id="navbar-logout"><a href="/logout"><i class="fa-solid fa-right-from-bracket"></i> Sair da conta</a></li>
                    ';

                if(isset($_SESSION['role_id']) && in_array($_SESSION['role_id'], [2, 3])) {
                    // Link para Dashboard do Proprietário
                    echo '
                    <li class="navbar-link">
                        <a href="/dashboard" aria-label="Acessar Dashboard do Proprietário">
                            <i class="fa-solid fa-building-user"></i> Dashboard
                        </a>
                    </li>
                    ';
                }
                    
                $defaultPicture = "assets/img/icones/default-user.jpg";
                if($_SESSION['user_picture'] !== '') {
                    $pictureUrl = $_SESSION['user_picture'];
                } else {
                    $pictureUrl = $defaultPicture;
                }


                // SE VOCÊ JÁ TEM $pictureUrl GARANTIDO, O ECHO FICA SIMPLES:
                echo '
                <li class="navbar-profile-picture">
                    <a href="/perfil"> 
                        <img src="' . htmlspecialchars($pictureUrl) . '" 
                            style="border-radius: 50%; width: 36px; height: 36px; object-fit: cover;" 
                            alt="Foto de Perfil do Usuário">
                    </a>
                </li>';
            } else {
                // Links do Usuário Deslogado
                echo '<li class="navbar-link"><a href="/cadastro">Cadastro</a></li>';
                echo '<li class="navbar-link"><a href="/login">Entrar</a></li>';
            }
            ?>
        </ul>
    </nav>
</header>