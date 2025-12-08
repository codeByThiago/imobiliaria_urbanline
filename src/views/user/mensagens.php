<?php
function formatMessageDate($dateString) {
    if (empty($dateString) || $dateString === '0' || $dateString === false) {
        return '';
    }

    try {
        $date = new DateTime($dateString);
        return $date->format('d/m/Y, H:i');
    } catch (Exception $e) {
        return '';
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/mensagens.css">
    <script src="https://kit.fontawesome.com/412e60f1e0.js" crossorigin="anonymous"></script>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <title>Urbanline Imóveis - Página Inicial</title>
</head>
<body>
    <?php include VIEWS . 'includes/alert.php'; ?>
    <?php include VIEWS . 'includes/navbar.php'; ?>

    <main class="mensagens-page">
        <div class="mensagens-container">

            <div class="mensagens-header">
                <h2>Mensagens de Clientes</h2>
                <p><?php echo $totalNaoLidas ?? 0; ?> Mensagens não lidas</p>
            </div>

            <div class="mensagens-content">
                
                <!-- INBOX -->
                <div class="inbox">
                    <h3>Inbox</h3>
                    <p class="inbox-subtitle">Todas as suas mensagens</p>

                    <ul class="mensagens-list">

                        <?php if (!empty($mensagens) && is_array($mensagens)): ?>
                            <?php foreach ($mensagens as $msg): 

                                if (!is_array($msg)) { continue; } // segurança

                                $isNova      = !empty($msg['lida']) ? false : true;
                                $isSelected  = isset($mensagemDetalhe) && !empty($mensagemDetalhe['id']) && $mensagemDetalhe['id'] == ($msg['id'] ?? null);
                                
                                $remetenteNome = $msg['remetente_nome'] ?? 'Urbanline Imóveis';
                                $remetenteSigla = strtoupper(
                                    substr($remetenteNome, 0, 1) . substr(strstr($remetenteNome, ' ') ?: $remetenteNome, 1, 1)
                                );

                            ?>
                                <li class="mensagem-item <?php echo $isNova ? 'nova' : ''; ?> <?php echo $isSelected ? 'selected' : ''; ?>">
                                    <a href="?id=<?php echo $msg['id']; ?>&p=<?php echo $currentPage; ?>" class="mensagem-link">
                                        
                                        <div class="remetente-icon <?php echo $isNova ? 'nova' : ''; ?>">
                                            <?php echo htmlspecialchars($remetenteSigla); ?>
                                        </div>

                                        <div class="mensagem-info">

                                            <div class="mensagem-topo">
                                                <span class="remetente-nome">
                                                    <?php echo htmlspecialchars($remetenteNome); ?>
                                                </span>

                                                <?php if ($isNova): ?>
                                                    <span class="tag-nova">Nova</span>
                                                <?php endif; ?>
                                            </div>

                                            <p class="mensagem-titulo">
                                                <?php echo htmlspecialchars($msg['titulo'] ?? ''); ?>
                                            </p>

                                            <span class="mensagem-data">
                                                <?php echo formatMessageDate($msg['created_at'] ?? null); ?>
                                            </span>

                                        </div>
                                    </a>
                                </li>

                            <?php endforeach; ?>

                        <?php else: ?>
                            <li class="mensagem-vazia">Nenhuma mensagem encontrada.</li>
                        <?php endif; ?>

                    </ul>

                    <!-- PAGINAÇÃO -->
                    <?php if (!empty($totalPages) && $totalPages > 1): ?>
                        <div class="paginacao">

                            <?php if (!empty($currentPage) && $currentPage > 1): ?>
                                <a href="?p=<?php echo $currentPage - 1; ?>" class="btn-paginacao">Página Anterior</a>
                            <?php endif; ?>

                            <span>Página <?php echo $currentPage; ?> de <?php echo $totalPages; ?></span>

                            <?php if ($currentPage < $totalPages): ?>
                                <a href="?p=<?php echo $currentPage + 1; ?>" class="btn-paginacao proximo">Próxima</a>
                            <?php endif; ?>

                        </div>
                    <?php endif; ?>

                </div>

                <!-- MENSAGEM DETALHADA -->
                <div class="mensagem-detalhe">

                    <?php if (!empty($mensagemDetalhe) && is_array($mensagemDetalhe)): 
                        $detalheRemetenteNome = $mensagemDetalhe['remetente_nome'] ?? 'Urbanline Imóveis';
                        $detalheRemetenteSigla = strtoupper(
                            substr($detalheRemetenteNome, 0, 1) . substr(strstr($detalheRemetenteNome, ' ') ?: $detalheRemetenteNome, 1, 1)
                        );
                    ?>

                        <div class="detalhe-header">

                            <div class="detalhe-remetente-icon">
                                <?php echo htmlspecialchars($detalheRemetenteSigla); ?>
                            </div>

                            <div class="detalhe-info">
                                <h3><?php echo htmlspecialchars($mensagemDetalhe['titulo'] ?? ''); ?></h3>
                                <p class="detalhe-remetente-nome"><?php echo htmlspecialchars($detalheRemetenteNome); ?></p>
                            </div>

                            <span class="detalhe-data">
                                <?php echo formatMessageDate($mensagemDetalhe['created_at'] ?? null); ?>
                            </span>

                        </div>

                        <div class="detalhe-body">
                            <p><?php echo nl2br(htmlspecialchars($mensagemDetalhe['mensagem'] ?? '')); ?></p>

                            <div class="area-resposta">
                                <textarea placeholder="Digite sua resposta..."></textarea>

                                <div class="resposta-botoes">
                                    <button class="btn-responder">Enviar Resposta</button>
                                    <button class="btn-cancelar">Cancelar</button>
                                </div>
                            </div>
                        </div>

                    <?php else: ?>
                        <div class="placeholder-vazio">
                            <p>Selecione uma mensagem para visualizar</p>
                        </div>
                    <?php endif; ?>

                </div>

            </div>
        </div>
    </main>

    <?php include VIEWS . 'includes/footer.php'; ?>

</body>
</html>
