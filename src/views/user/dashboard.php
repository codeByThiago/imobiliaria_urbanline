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
    
    <nav class="main-menu">
        <ul>
            <li class="active"><a href="#"><i class="fas fa-chart-line"></i> Dashboard</a></li>
            <li><a href="#"><i class="fas fa-plus-circle"></i> Cadastrar</a></li>
            <li><a href="#"><i class="fas fa-building"></i> Imóveis</a></li>
            <li><a href="#"><i class="fas fa-envelope"></i> Mensagens</a></li>
            <li><a href="#"><i class="fas fa-history"></i> Histórico</a></li>
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
                    <p class="kpi-value">24</p>
                    <p class="kpi-detail">+3 desde o último mês</p>
                </div>
            </div>

            <div class="kpi-card">
                <div class="card-header">
                    <h3 class="card-title">Mensagens</h3>
                    <i class="fas fa-comment-dots card-icon"></i>
                </div>
                <div class="card-body">
                    <p class="kpi-value">18</p>
                    <p class="kpi-detail kpi-warning">5 não lidas</p>
                </div>
            </div>

            <div class="kpi-card">
                <div class="card-header">
                    <h3 class="card-title">Visualizações</h3>
                    <i class="fas fa-eye card-icon"></i>
                </div>
                <div class="card-body">
                    <p class="kpi-value">1,234</p>
                    <p class="kpi-detail kpi-success">+12% em relação ao mês anterior</p>
                </div>
            </div>

            <div class="kpi-card">
                <div class="card-header">
                    <h3 class="card-title">Contatos Ativos</h3>
                    <i class="fas fa-user-tie card-icon"></i>
                </div>
                <div class="card-body">
                    <p class="kpi-value">42</p>
                    <p class="kpi-detail">8 novos esta semana</p>
                </div>
            </div>
        </div>

        <div class="charts-grid">
            
            <div class="chart-card chart-bar">
                <h3 class="card-title">Vendas Mensais</h3>
                <p class="chart-subtitle">Número de imóveis vendidos por mês</p>
                <div class="chart-area">
                                    </div>
            </div>

            <div class="chart-card chart-line">
                <h3 class="card-title">Visualizações de Imóveis</h3>
                <p class="chart-subtitle">Engajamento dos últimos 6 meses</p>
                <div class="chart-area">
                                    </div>
            </div>
        </div>
    </main>

    <footer class="footer">
        © 2025 UrbanLine. Todos os direitos reservados.
    </footer>
</body>
</html>