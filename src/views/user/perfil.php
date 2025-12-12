<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/perfil.css">
    <script src="https://kit.fontawesome.com/412e60f1e0.js" crossorigin="anonymous"></script>
    <title>Perfil de <?php echo htmlspecialchars($user['nome'] ?? 'Usuário'); ?></title>
</head>
<body>
    <?php include VIEWS . 'includes/navbar.php';?>
    
    <?php 
        $defaultPicture = "assets/img/icones/default-user.jpg";
        if($_SESSION['user_picture'] !== '') {
            $pictureUrl = $_SESSION['user_picture'];
        } else {
            $pictureUrl = $defaultPicture;
        }
    ?>

    <main class="profile-page">
        <section class="profile-card">
            <div class="profile-header">
                <img 
                    src="<?php echo htmlspecialchars($pictureUrl) ?>" 
                    alt="Foto de Perfil" 
                    class="profile-picture"
                >
                <h1 class="profile-name"><?php echo htmlspecialchars($user['nome'] ?? 'Nome Indisponível'); ?></h1>
                <p class="profile-role">
                    <i class="fa-solid fa-user-tag"></i> 
                    <?php 
                        if($user['role_id'] === 2) {
                            echo 'Proprietário';
                        } elseif ($user['role_id'] === 3) {
                            echo 'Administrador';
                        } else {
                            echo 'Cliente';
                        }
                    ?>
                </p>
            </div>

            <div class="profile-details">
                <h2 class="section-title">Informações de Contato</h2>
                <div class="detail-item">
                    <i class="fa-solid fa-envelope icon-gold"></i>
                    <span><?php echo htmlspecialchars($user['email'] ?? 'N/A'); ?></span>
                </div>
                <div class="detail-item">
                    <i class="fa-solid fa-phone icon-gold"></i>
                    <span><?php echo htmlspecialchars($user['telefone'] ?? 'Não informado'); ?></span>
                </div>
                <div class="detail-item">
                    <i class="fa-solid fa-id-card icon-gold"></i>
                    <span><?php echo htmlspecialchars($user['cpf'] ?? 'Não informado'); ?></span>
                </div>
            </div>

            <?php if ($endereco): ?>
            <div class="profile-details">
                <h2 class="section-title">Endereço</h2>
                <div class="detail-item">
                    <i class="fa-solid fa-location-dot icon-gold"></i>
                    <span><?php echo htmlspecialchars($endereco['logradouro'] ?? ''); ?>, <?php echo htmlspecialchars($endereco['numero'] ?? ''); ?></span>
                </div>
                <div class="detail-item">
                    <i class="fa-solid fa-city icon-gold"></i>
                    <span><?php echo htmlspecialchars($endereco['bairro'] ?? ''); ?> - <?php echo htmlspecialchars($endereco['cidade'] ?? ''); ?>/<?php echo htmlspecialchars($endereco['uf'] ?? ''); ?></span>
                </div>
                <div class="detail-item">
                    <i class="fa-solid fa-map-pin icon-gold"></i>
                    <span>CEP: <?php echo htmlspecialchars($endereco['cep'] ?? ''); ?></span>
                </div>
            </div>
            <?php endif; ?>

            <div class="profile-actions">
                <a href="/editar-perfil" class="action-button primary-button">
                    <i class="fa-solid fa-pen-to-square"></i> Editar Perfil
                </a>
                
                <?php if ($canManageImoveis): ?>
                    <a href="/dashboard-proprietario" class="action-button secondary-button">
                        <i class="fa-solid fa-building-user"></i> Gerenciar Imóveis
                    </a>
                <?php endif; ?>

                <a href="/mensagens" class="action-button secondary-button">
                    <i class="fa-solid fa-comment"></i> Minhas Mensagens
                </a>
                
                <a href="/logout" class="action-button logout-button">
                    <i class="fa-solid fa-right-from-bracket"></i> Sair
                </a>
            </div>
        </section>
    </main>

    <?php include VIEWS . 'includes/footer.php';?>
</body>
</html>