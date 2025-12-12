<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Imóveis - UrbanLine</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <script src="https://kit.fontawesome.com/412e60f1e0.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php include VIEWS . 'includes/navbar.php';?>
    <?php include VIEWS . 'includes/alert.php';?>

    
    <nav class="main-menu">
        <ul>
            <li><a href="dashboard"><i class="fas fa-chart-line"></i> Dashboard</a></li>
            <li><a href="cadastrar-imovel"><i class="fas fa-plus-circle"></i> Cadastrar</a></li>
            <li class="active"><a href="imoveis-proprietario"><i class="fas fa-building"></i> Imóveis</a></li> </ul>
    </nav>

    <main class="dashboard-container">
        <h1 class="dashboard-title">Meus Imóveis Cadastrados</h1>
        <p class="dashboard-subtitle">Gerencie, edite e acompanhe o status dos seus anúncios.</p>
        
        <div class="imovel-list-actions">
            <a href="cadastrar-imovel" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Novo Cadastro</a>
            </div>

        <?php if (empty($imoveis)): ?>
            <div class="no-results-message">
                <i class="fas fa-exclamation-circle"></i>
                <p>Você ainda não tem nenhum imóvel cadastrado.</p>
                <a href="cadastrar-imovel" class="btn btn-secondary">Cadastrar Agora</a>
            </div>
        <?php else: ?>
            
            <div class="imoveis-grid">
                
                <?php foreach ($imoveis as $imovel): 
                    // Formatação de valores para exibição
                    $valorFormatado = 'R$ ' . number_format($imovel['valor'], 2, ',', '.');
                    $statusClass = $imovel['status'] === 'disponivel' ? 'status-disponivel' : 'status-vendido';
                    $statusTexto = $imovel['status'] === 'disponivel' ? 'Disponível' : 'Vendido/Alugado';
                ?>
                
                    <div class="imovel-card-owner">
                        <div class="imovel-header">
                            <img src="<?php echo $imovel['foto_principal'] ?? 'assets/img/placeholder.jpg'; ?>" alt="Foto Principal do Imóvel" class="imovel-thumbnail">
                            <span class="imovel-status <?php echo $statusClass; ?>"><?php echo $statusTexto; ?></span>
                        </div>
                        
                        <div class="imovel-details">
                            <h3 class="imovel-title"><?php echo htmlspecialchars($imovel['nome']); ?></h3>
                            <p class="imovel-location"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($imovel['cidade']); ?> - <?php echo htmlspecialchars($imovel['uf']); ?></p>
                            
                            <div class="imovel-info-row">
                                <p class="info-item"><i class="fas fa-tag"></i> <?php echo htmlspecialchars($imovel['tipo_imovel']); ?></p>
                                <p class="info-item"><i class="fas fa-ruler-combined"></i> <?php echo number_format($imovel['area'], 0, ',', '.') . ' m²'; ?></p>
                            </div>
                            
                            <p class="imovel-price"><?php echo $valorFormatado; ?></p>
                        </div>

                        <div class="imovel-actions">
                            <a href="editar-imovel?id=<?php echo $imovel['id']; ?>" class="btn btn-edit" title="Editar Imóvel">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <a href="detalhes-imovel?id=<?php echo $imovel['id']; ?>" class="btn btn-view" title="Ver Anúncio">
                                <i class="fas fa-eye"></i> Visualizar
                            </a>
                            <a href="excluir-imovel?id=<?php echo $imovel['id']; ?>" class="btn btn-delete" title="Excluir Imóvel" onclick="return confirm('Tem certeza que deseja excluir este imóvel?');">
                                <i class="fas fa-trash-alt"></i> Excluir
                            </a>
                        </div>
                    </div>

                <?php endforeach; ?>
                
            </div>
        <?php endif; ?>

    </main>

    <?php include VIEWS . 'includes/footer.php';?>
</body>
</html>