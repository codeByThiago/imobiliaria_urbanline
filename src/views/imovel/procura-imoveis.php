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
            <?php if ($totalPages > 1): 
                // Captura os filtros atuais para manter na URL da paginação
                $currentQuery = http_build_query(array_merge($_GET, ['page' => '']));
                $baseUrl = '?' . $currentQuery;
            ?>
            <nav class="paginacao-container" aria-label="Navegação de Resultados">
                <ul class="paginacao-lista">
                    <li class="paginacao-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                        <a href="<?= $baseUrl . 1 ?>" aria-label="Primeira Página" class="paginacao-link">
                            <i class="fas fa-chevron-left"></i>
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
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
                    <li class="paginacao-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                        <a href="<?= $baseUrl . $totalPages ?>" aria-label="Última Página" class="paginacao-link">
                            <i class="fas fa-chevron-right"></i>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                </ul>
                <p class="paginacao-info">Página <?= $currentPage ?> de <?= $totalPages ?></p>
            </nav>
            <?php endif; ?>
            <div class="filtros">
                <form action="" method="get">    
                    <div class="form-control">
                        <label for="ordenar-por">Ordenar por</label>
                        <select name="ordenar-por" id="ordenar-por">
                            <?php $o = $filters['ordenar-por'] ?? 'relevancia'; ?> 
                            <option value="relevancia" <?= $o === 'relevancia' ? 'selected' : '' ?>>Relevância</option>
                            <option value="menor-preco" <?= $o === 'menor-preco' ? 'selected' : '' ?>>Preço: menor</option>
                            <option value="maior-preco" <?= $o === 'maior-preco' ? 'selected' : '' ?>>Preço: maior</option>
                        </select>
                    </div>

                    <div class="form-control">
                        <label for="status-imovel">Status</label>
                        <select name="status-imovel" id="status-imovel">
                            <?php $s = $filters['status-imovel'] ?? ''; ?> 
                            <option value="" <?= $s === '' ? 'selected' : '' ?>>Todos</option>
                            <option value="disponivel" <?= $s === 'disponivel' ? 'selected' : '' ?>>Disponível</option>
                            <option value="vendido" <?= $s === 'vendido' ? 'selected' : '' ?>>Vendido</option>
                            <option value="alugado" <?= $s === 'alugado' ? 'selected' : '' ?>>Alugado</option>
                        </select>
                    </div>

                    <div class="form-control">
                        <label for="tipo">Tipo</label>
                        <select name="tipo" id="tipo">
                            <?php $s = $filters['tipo'] ?? ''; ?>
                            <option value="" <?= $s === '' ? 'selected' : '' ?>>Todos</option>
                            <option value="casa" <?= $s === 'casa' ? 'selected' : '' ?>>Casa</option>
                            <option value="apartamento" <?= $s === 'apartamento' ? 'selected' : '' ?>>Apartamento</option>
                            <option value="kitnet" <?= $s === 'kitnet' ? 'selected' : '' ?>>Kitnet</option>
                            <option value="sobrado" <?= $s === 'sobrado' ? 'selected' : '' ?>>Sobrado</option>
                            <option value="terreno" <?= $s === 'terreno' ? 'selected' : '' ?>>Terreno</option>
                            <option value="comercial" <?= $s === 'comercial' ? 'selected' : '' ?>>Comercial</option>
                        </select>
                    </div>

                    <div class="form-control">
                        <label for="valor">Faixa de valor</label>
                        <select name="valor" id="valor">
                            <?php $s = $filters['valor'] ?? ''; ?>
                            <option value="" <?= $s === '' ? 'selected' : '' ?>>Selecione</option>
                            <option value="0-100k" <?= $s === '0-100k' ? 'selected' : '' ?>>Até R$100.000</option>
                            <option value="100k-300k" <?= $s === '100k-300k' ? 'selected' : '' ?>>R$100.000 - R$300.000</option>
                            <option value="300k-500k" <?= $s === '300k-500k' ? 'selected' : '' ?>>R$300.000 - R$500.000</option>
                            <option value="500k-1m" <?= $s === '500k-1m' ? 'selected' : '' ?>>R$500.000 - R$1.000.000</option>
                            <option value="1m+" <?= $s === '1m+' ? 'selected' : '' ?>>Acima de R$1.000.000</option>
                        </select>
                    </div>

                    <div class="form-control">
                        <label for="quartos">Quartos</label>
                        <select name="quartos" id="quartos">
                            <?php $q = $filters['quartos'] ?? ''; ?>
                            <option value=""> <?= $q == 1 ? 'selected' : '' ?>Selecione</option>
                            <option value="1" <?= $q == 1 ? 'selected' : '' ?>>1 quarto</option>
                            <option value="2" <?= $q == 2 ? 'selected' : '' ?>>2 quartos</option>
                            <option value="3" <?= $q == 3 ? 'selected' : '' ?>>3 quartos</option>
                            <option value="4+" <?= $q == '4+' ? 'selected' : '' ?>>4 ou mais</option>
                        </select>
                    </div>

                    <div class="form-control">
                        <label for="banheiros">Banheiros</label>
                        <select name="banheiros" id="banheiros">
                            <?php $q = $filters['banheiros'] ?? ''; ?>
                            <option value=""<?= $q == '' ? 'selected' : '' ?>>Selecione</option>
                            <option value="1"<?= $q == 1 ? 'selected' : '' ?>>1 banheiro</option>
                            <option value="2"<?= $q == 2 ? 'selected' : '' ?>>2 banheiros</option>
                            <option value="3"<?= $q == 3 ? 'selected' : '' ?>>3 banheiros</option>
                            <option value="4+"<?= $q == '4+' ? 'selected' : '' ?>>4 ou mais</option>
                        </select>
                    </div>

                    <div class="form-control">
                        <label for="cozinhas">Cozinhas</label>
                        <select name="cozinhas" id="cozinhas">
                            <?php $c = $filters['cozinhas'] ?? ''; ?>
                            <option value=""<?=  $c == '' ? 'selected' : '' ?>>Selecione</option>
                            <option value="1"<?=  $c == 1 ? 'selected' : '' ?>>1 cozinha</option>
                            <option value="2"<?=  $c == 2 ? 'selected' : '' ?>>2 cozinhas</option>
                            <option value="3+"<?=  $c == '3+' ? 'selected' : '' ?>>3 ou mais</option>
                        </select>
                    </div>

                    <div class="form-control">
                        <label for="piscinas">Piscinas</label>
                        <select name="piscinas" id="piscinas">
                            <?php $p = $filters['piscinas'] ?? ''; ?>
                            <option value=""<?=  $p == '' ? 'selected' : '' ?>>Selecione</option>
                            <option value="0"<?=  $p == 0 ? 'selected' : '' ?>>Nenhuma</option>
                            <option value="1"<?=  $p == 1 ? 'selected' : '' ?>>1 piscina</option>
                            <option value="2+"<?=  $p == '2+' ? 'selected' : '' ?>>2 ou mais</option>
                        </select>
                    </div>

                    <div class="form-control">
                        <label for="vagas-de-garagem">Vagas de Garagem</label>
                        <select name="vagas-de-garagem" id="vagas-de-garagem">
                            <?php $v = $filters['vagas-de-garagem'] ?? ''; ?>
                            <option value=""<?=  $v == '' ? 'selected' : '' ?>>Selecione</option>
                            <option value="1"<?=  $v == 1 ? 'selected' : '' ?>>1 vaga</option>
                            <option value="2"<?=  $v == 2 ? 'selected' : '' ?>>2 vagas</option>
                            <option value="3"<?=  $v == 3 ? 'selected' : '' ?>>3 vagas</option>
                            <option value="4+"<?=  $v == '4+' ? 'selected' : '' ?>>4 ou mais</option>
                        </select>
                    </div>
                    
                    <div class="form-control">
                        <label for="mobiliado">Mobiliado</label>
                        <select name="mobiliado" id="mobiliado">
                            <?php $m = $filters['mobiliado'] ?? ''; ?>
                            <option value=""<?=  $m === '' ? 'selected' : '' ?>>Selecione</option>
                            <option value="1"<?=  $m == 1 ? 'selected' : '' ?>>Sim</option>
                            <option value="0"<?=  $m == 0 && $m !== '' ? 'selected' : '' ?>>Não</option>
                        </select>
                    </div>

                    <button type="submit" id="procurar-imovel-btn">Procurar</button>
                </form>
            </div>
            <?php if (empty($imoveis)): ?>
            <div class="no-results-message">
                <h2>😔 Nenhum imóvel encontrado.</h2>
                <p>Tente ajustar ou remover alguns dos filtros aplicados. Seu resultado de busca não retornou nenhum imóvel.</p>
            </div>
            <?php endif; ?>
            <!-- GRID DE IMÓVEIS -->
            <div class="grid-imoveis">
                <?php foreach ($imoveis as $imovel) {
                    $imovelId = $imovel['id'];
                    $fotoUrl = $fotos_por_imovel[$imovelId]; // Usa o valor mapeado do Controller
                ?>
                <div class="card-imovel">
                    <?php if (!empty($fotoUrl)): ?>
                        <img src="<?= htmlspecialchars($fotoUrl) ?>" alt="Foto principal do imóvel" loading="lazy">
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
        </section>
        <script src="assets/js/main.js"></script>
        <script src="assets/js/menu.js"></script>
    </main>
    <?php include VIEWS . 'includes/footer.php';?>
</body>
</html>

