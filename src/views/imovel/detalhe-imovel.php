<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <script src="https://kit.fontawesome.com/412e60f1e0.js" crossorigin="anonymous"></script>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/detalhe-imovel.css">
    <title>Urbanline Imóveis - Detalhe de Imóvel</title>
</head>
<body>
<body>
    <?php include VIEWS . 'includes/navbar.php';?>
    <?php include VIEWS . 'includes/alert.php';?>
    <section id="detalhe">
        <div class="container">
            <div class="carrosel-imovel">
                <div class="fotos-wrapper">
                    <?php if (!empty($fotos)) {
                        foreach ($fotos as $imovel_foto) { ?>
                            <img src="assets/img/full/<?= $imovel_foto['url']?>" alt="Foto do Imóvel" class="imovel-foto">
                        <?php }
                    } else { ?>
                        <img src="assets/img/placeholder.png" alt="Sem fotos disponíveis" class="imovel-foto">
                    <?php } ?>
                </div>

                <button class="nav-btn prev" aria-label="Foto anterior"><i class="fas fa-chevron-left"></i></button>
                <button class="nav-btn next" aria-label="Próxima foto"><i class="fas fa-chevron-right"></i></button>

                <div class="carousel-indicators"></div>
            </div>

            <div class="info-principal">
                <h1 class="title-luxo"><?= $imovel['nome']?></h1>
                <p class="valor">R$ <?= number_format($imovel['valor'], 2, ',', '.') ?></p>
                <p class="tipo"><?= ucfirst($imovel['tipo_imovel']) ?> | Status: <span class="status-<?= $imovel['status'] ?>"><?= ucfirst($imovel['status']) ?></span></p>
                <div class="endereco">
                    <i class="fas fa-map-marker-alt"></i> 
                    <?= $endereco['logradouro']?>, <?= $endereco['numero']?> - <?= $endereco['bairro']?>, <?= $endereco['cidade']?>/<?= $endereco['uf']?>
                </div>
                <div class="detalhes-basicos">
                    <span><i class="fas fa-bed"></i> Quartos: <strong><?= $imovel['quant_quartos']?></strong></span>
                    <span><i class="fas fa-bath"></i> Banheiros: <strong><?= $imovel['quant_banheiros']?></strong></span>
                    <span><i class="fas fa-car"></i> Vagas: <strong><?= $imovel['vagas_garagem']?></strong></span>
                    <span><i class="fas fa-ruler-combined"></i> Área: <strong><?= $imovel['area']?> m²</strong></span>
                </div>
            </div>

            <div class="separador-luxo"></div>

            <div class="descricao">
                <h2 class="title-luxo">Descrição</h2>
                <p><?= nl2br($imovel['descricao']) ?></p>
            </div>
            
            <div class="separador-luxo"></div>

            <div class="detalhes-adicionais">
                <h2 class="title-luxo">Detalhes</h2>
                <ul class="grid-detalhes">
                    <li>Condição: <span><?= ucfirst(str_replace('_', ' ', $imovel['condicao'])) ?></span></li>
                    <li>Suítes: <span><?= $imovel['quant_suites']?></span></li>
                    <li>Cozinhas: <span><?= $imovel['quant_cozinhas']?></span></li>
                    <li>Piscina: <span><?= $imovel['quant_piscinas'] > 0 ? 'Sim' : 'Não' ?></span></li>
                    <li>Mobiliado: <span><?= $imovel['mobiliado'] ? 'Sim' : 'Não' ?></span></li>
                </ul>
            </div>

            <div class="separador-luxo"></div>

            <?php if (!empty($proprietario)) { ?>
                <div class="proprietario-info">
                    <h2 class="title-luxo">Proprietário</h2>
                    <div class="proprietario-card">
                        <?php 
                            // Define o caminho da imagem: se 'foto_url' estiver vazio, usa 'default-user.jpg'
                            $foto_proprietario = $proprietario['picture'] ?: 'default-user.jpg';
                        ?>
                        
                        <img src="assets/img/icones/<?= $foto_proprietario ?>" alt="Foto do Proprietário">
                        
                        <div class="proprietario-details">
                            <p>**Nome:** <span><?= $proprietario['nome']?></span></p>
                            <p>**Contato:** <span><?= $proprietario['telefone']?></span></p>
                            <p>**Email:** <span><?= $proprietario['email']?></span></p>
                        </div>
                        
                        <div class="proprietario-acao">
                            <a href="#" class="btn-contato">
                                <i class="fas fa-phone"></i> Entrar em Contato
                            </a>
                        </div>
                        
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </section>
    <script src="assets/js/menu.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
        const wrapper = document.querySelector('.fotos-wrapper');
        const fotos = document.querySelectorAll('.imovel-foto');
        const prevBtn = document.querySelector('.nav-btn.prev');
        const nextBtn = document.querySelector('.nav-btn.next');
        const indicatorsContainer = document.querySelector('.carousel-indicators');

        if (fotos.length === 0) return; // Não faz nada se não houver fotos

        let currentIndex = 0;
        const totalFotos = fotos.length;

        // Função para atualizar a exibição do carrossel
        function updateCarousel() {
            const offset = -currentIndex * 100; // Move o wrapper em porcentagem
            wrapper.style.transform = `translateX(${offset}%)`;
            updateIndicators();
        }

        // Função para atualizar os indicadores (bolinhas)
        function updateIndicators() {
            indicatorsContainer.innerHTML = '';
            fotos.forEach((_, index) => {
                const indicator = document.createElement('span');
                indicator.classList.add('indicator');
                if (index === currentIndex) {
                    indicator.classList.add('active');
                }
                indicator.addEventListener('click', () => {
                    currentIndex = index;
                    updateCarousel();
                });
                indicatorsContainer.appendChild(indicator);
            });
        }

        // Evento para o botão PRÓXIMO
        nextBtn.addEventListener('click', () => {
            currentIndex = (currentIndex + 1) % totalFotos;
            updateCarousel();
        });

        // Evento para o botão ANTERIOR
        prevBtn.addEventListener('click', () => {
            currentIndex = (currentIndex - 1 + totalFotos) % totalFotos;
            updateCarousel();
        });

        updateCarousel(); 
    });
    </script>
</body>
</html>