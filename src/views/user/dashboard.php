<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard do Proprietário - UrbanLine</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <script src="https://kit.fontawesome.com/412e60f1e0.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php include VIEWS . 'includes/navbar.php';?>
    <?php include VIEWS . 'includes/alert.php';?>

    
    <nav class="main-menu">
        <ul>
            <li class="active"><a href="dashboard"><i class="fas fa-chart-line"></i> Dashboard</a></li>
            <li><a href="cadastrar-imovel"><i class="fas fa-plus-circle"></i> Cadastrar</a></li>
            <li><a href="imoveis-proprietario"><i class="fas fa-building"></i> Imóveis</a></li>
        </ul>
    </nav>

    <main class="dashboard-container">
        <h1 class="dashboard-title">Dashboard do Proprietário</h1>
        <p class="dashboard-subtitle">Visão geral do seu portfólio de imóveis</p>
        
        <div class="kpi-grid">
            
            <div class="kpi-card">
                <div class="card-header">
                    <h3 class="card-title">Total de Imóveis</h3>
                    <i class="fas fa-home card-icon"></i>
                </div>
                <div class="card-body">
                    <p class="kpi-value"><?php echo $totalImoveis ?? 0; ?></p>
                    <p class="kpi-detail">Todos os seus registros</p>
                </div>
            </div>

            <div class="kpi-card kpi-messages">
                <div class="card-header">
                    <h3 class="card-title">Mensagens Não Lidas</h3>
                    <i class="fas fa-envelope-open-text card-icon"></i>
                </div>
                <div class="card-body">
                    <p class="kpi-value kpi-warning"><?php echo $totalMensagensNaoLidas ?? 0; ?></p>
                    <p class="kpi-detail">Pendentes de resposta</p>
                </div>
            </div>

            <div class="kpi-card kpi-available">
                <div class="card-header">
                    <h3 class="card-title">Disponíveis para Venda/Aluguel</h3>
                    <i class="fas fa-check-circle card-icon"></i>
                </div>
                <div class="card-body">
                    <p class="kpi-value"><?php echo $imoveisDisponiveis ?? 0; ?></p>
                    <p class="kpi-detail">Atualmente no ar</p>
                </div>
            </div>

            <div class="kpi-card kpi-monthly">
                <div class="card-header">
                    <h3 class="card-title">Cadastros Este Mês</h3>
                    <i class="fas fa-calendar-alt card-icon"></i>
                </div>
                <div class="card-body">
                    <p class="kpi-value"><?php echo $imoveisCadastradosMes ?? 0; ?></p>
                    <p class="kpi-detail">Novos imóveis adicionados</p>
                </div>
            </div>
            
        </div>

        <div class="quick-actions-grid">
            <h2 class="dashboard-subtitle">Ações Rápidas</h2>
            
            <a href="cadastrar-imovel" class="action-card">
                <i class="fas fa-plus-square action-icon"></i>
                <h3 class="action-title">Novo Imóvel</h3>
                <p class="action-description">Cadastre um novo imóvel no seu portfólio.</p>
            </a>

            <a href="mensagens" class="action-card">
                <i class="fas fa-inbox action-icon"></i>
                <h3 class="action-title">Caixa de Entrada</h3>
                <p class="action-description">Visualize e responda todas as suas mensagens.</p>
            </a>

        </div>
    </main>

    <footer class="footer">
        © 2025 UrbanLine. Todos os direitos reservados.
    </footer>
</body>
</html>