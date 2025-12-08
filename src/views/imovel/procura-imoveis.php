<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/grid-imoveis.css">
    <script src="https://kit.fontawesome.com/412e60f1e0.js" crossorigin="anonymous"></script>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <title>Urbanline Imóveis - Página Inicial</title>
</head>
<body>
    <?php include VIEWS . 'includes/navbar.php';?>
    <?php include VIEWS . 'includes/alert.php';?>

    <main>
        <section class="procura-imoveis">
            <div class="titulo">
                <h1>Procurar Imóveis</h1>
                <div class="search-input">
                    <label for="search-input" class="sr-only">Procurar...</label>
                    <input type="text" name="search-input" id="search-input" placeholder="Buscar...">
                </div>
            </div>

            <div class="filtros">
                <form action="" method="get">    
                    <div class="form-control">
                        <label for="ordenar-por">Ordenar por</label>
                        <select name="ordernar-por" id="ordenar-por">
                            <option value="relevancia">Relevância</option>
                            <option value="menor-preco">Preço: menor</option>
                            <option value="maior-preco">Preço: maior</option>
                        </select>
                    </div>

                    <div class="form-control">
                        <label for="status-imovel">Status</label>
                        <select name="status-imovel" id="status-imovel">
                            <option value="" <?= ($filters['status-imovel'] ?? '') === '' ? 'selected' : '' ?>>Todos</option>
                            
                            <option value="disponivel" <?= ($filters['status-imovel'] ?? '') === 'aluguel' ? 'selected' : '' ?>>Disponível</option>
                            
                            <option value="vendido" <?= ($filters['status-imovel'] ?? '') === 'venda' ? 'selected' : '' ?>>Vendido</option>
                            
                            <option value="alugado" <?= ($filters['status-imovel'] ?? '') === 'alugado' ? 'selected' : '' ?>>Alugado</option>
                            
                        </select>
                    </div>

                    <div class="form-control">
                        <label for="tipo">Tipo</label>
                        <select name="tipo" id="tipo">
                            <option selected value="">Todos</option>
                            <option value="casa">Casa</option>
                            <option value="apartamento">Apartamento</option>
                            <option value="kitnet">Kitnet</option>
                            <option value="sobrado">Sobrado</option>
                            <option value="terreno">Terreno</option>
                            <option value="comercial">Comercial</option>
                        </select>
                    </div>

                    <div class="form-control">
                        <label for="valor">Faixa de valor</label>
                        <select name="valor" id="valor">
                            <option value="">Selecione</option>
                            <option value="0-100k">Até R$100.000</option>
                            <option value="100k-300k">R$100.000 - R$300.000</option>
                            <option value="300k-500k">R$300.000 - R$500.000</option>
                            <option value="500k-1m">R$500.000 - R$1.000.000</option>
                            <option value="1m+">Acima de R$1.000.000</option>
                        </select>
                    </div>

                    <div class="form-control">
                        <label for="quartos">Quartos</label>
                        <select name="quartos" id="quartos">
                            <option value="">Selecione</option>
                            <?php $q = $filters['quartos'] ?? ''; ?>
                            <option value="1" <?= $q == 1 ? 'selected' : '' ?>>1 quarto</option>
                            <option value="2" <?= $q == 2 ? 'selected' : '' ?>>2 quartos</option>
                            <option value="3" <?= $q == 3 ? 'selected' : '' ?>>3 quartos</option>
                            <option value="4+" <?= $q == '4+' ? 'selected' : '' ?>>4 ou mais</option>
                        </select>
                    </div>

                    <div class="form-control">
                        <label for="banheiros">Banheiros</label>
                        <select name="banheiros" id="banheiros">
                            <option value="">Selecione</option>
                            <option value="1">1 banheiro</option>
                            <option value="2">2 banheiros</option>
                            <option value="3">3 banheiros</option>
                            <option value="4+">4 ou mais</option>
                        </select>
                    </div>

                    <div class="form-control">
                        <label for="cozinhas">Cozinhas</label>
                        <select name="cozinhas" id="cozinhas">
                            <option value="">Selecione</option>
                            <option value="1">1 cozinha</option>
                            <option value="2">2 cozinhas</option>
                            <option value="3+">3 ou mais</option>
                        </select>
                    </div>

                    <div class="form-control">
                        <label for="piscinas">Piscinas</label>
                        <select name="piscinas" id="piscinas">
                            <option value="">Selecione</option>
                            <option value="0">Nenhuma</option>
                            <option value="1">1 piscina</option>
                            <option value="2+">2 ou mais</option>
                        </select>
                    </div>

                    <div class="form-control">
                        <label for="vagas-de-garagem">Vagas de Garagem</label>
                        <select name="vagas-de-garagem" id="vagas-de-garagem">
                            <option value="">Selecione</option>
                            <option value="1">1 vaga</option>
                            <option value="2">2 vagas</option>
                            <option value="3">3 vagas</option>
                            <option value="4+">4 ou mais</option>
                        </select>
                    </div>

                    <button type="submit" id="procurar-imovel-btn">Procurar</button>
                </form>
            </div>

            <!-- GRID DE IMÓVEIS -->
            <div class="grid-imoveis">
                <?php foreach ($imoveis as $imovel) {
                    $imovelId = $imovel['id'];
                    $fotoUrl = $fotos_por_imovel[$imovelId]; // Usa o valor mapeado do Controller
                ?>
                <div class="card-imovel">
                    <?php if (!empty($fotoUrl)): ?>
                        <img src="assets/img/thumbs/<?= htmlspecialchars($fotoUrl) ?>" alt="Foto principal do imóvel" loading="lazy">
                    <?php endif; ?>
                    <div class="info">
                        <h3><?= htmlspecialchars($imovel['nome'])?></h3> 
                        <span class="preco">R$ <?= number_format($imovel['valor'], 3, '.', '.') ?></span>
                        <div class="detalhes-imovel">
                            <div class="detalhe-item">
                                <span><?= $imovel['area'] ?> m²</span> <span>Área</span>
                            </div>
                            <div class="detalhe-item">
                                <span><?= $imovel['quant_quartos'] ?></span>
                                <span>Quartos</span>
                            </div>
                            <div class="detalhe-item">
                                <span><?= $imovel['quant_banheiros'] ?></span>
                                <span>Banheiros</span>
                            </div>
                            <div class="detalhe-item">
                                <span><?= $imovel['vagas_garagem'] ?? 0 ?></span> <span>Vagas</span>
                            </div>
                        </div>
                        
                        <?php 
                        switch ($imovel['status']) {
                            case 'disponivel':
                                echo "<span class='status disponivel'>Disponível</span>";
                                break;
                            case 'alugado':
                                echo "<span class='status alugado'>Alugado</span>";
                                break;
                            case 'vendido':
                                echo "<span class='status vendido'>Vendido</span>";
                                break;
                        }
                        ?>
                        <div class="acoes">
                            <a href="detalhe-imovel?id=<?= htmlspecialchars($imovel['id']) ?>" class="ver-detalhes">Ver Detalhes</a>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
            </div>

            <?php if ($totalPages > 1): 
                // Captura os filtros atuais para manter na URL da paginação
                $currentQuery = http_build_query(array_merge($_GET, ['page' => '']));
                $baseUrl = '?' . $currentQuery;
            ?>
            <nav class="paginacao-container" aria-label="Navegação de Resultados">
                <ul class="paginacao-lista">
                    <li class="paginacao-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                        <a href="<?= $baseUrl . ($currentPage - 1) ?>" aria-label="Anterior" class="paginacao-link">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>

                    <?php 
                    $startPage = max(1, $currentPage - 2);
                    $endPage = min($totalPages, $currentPage + 2);

                    for ($i = $startPage; $i <= $endPage; $i++): 
                    ?>
                        <li class="paginacao-item <?= $i == $currentPage ? 'active' : '' ?>">
                            <a href="<?= $baseUrl . $i ?>" class="paginacao-link"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <li class="paginacao-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                        <a href="<?= $baseUrl . ($currentPage + 1) ?>" aria-label="Próxima" class="paginacao-link">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                </ul>
                <p class="paginacao-info">Página <?= $currentPage ?> de <?= $totalPages ?></p>
            </nav>
            <?php endif; ?>
        </section>
        <script src="assets/js/main.js"></script>
        <script src="assets/js/menu.js"></script>
    </main>
    <?php include VIEWS . 'includes/footer.php';?>
</body>
</html>