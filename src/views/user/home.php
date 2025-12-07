<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/home.css">
    <script src="https://kit.fontawesome.com/412e60f1e0.js" crossorigin="anonymous"></script>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <title>Urbanline Imóveis - Página Inicial</title>
</head>
<body class="inicial">

<?php include VIEWS . 'includes/navbar.php';?>
<?php include VIEWS . 'includes/alert.php';?>

    <main>
        <section class="background-image-container">
            <img src="assets/img/carrosel/carrosel-1.jpg" alt="Fundo animado" class="background-image active">
            <img src="assets/img/carrosel/carrosel-2.jpg" alt="Fundo animado" class="background-image active">
            <img src="assets/img/carrosel/carrosel-3.jpg" alt="Fundo animado" class="background-image active">
            <div class="dark-overlay"></div>
            <div class="form-container">
                <h1>Onde a Arquitetura Encontra o Seu Estilo de Vida</h1>
                <p>Explore nossas opções e descubra imóveis que se encaixam no seu estilo de vida.</p>
                <form action="procura-imoveis.php" method="post" class="search-form">
                    <!-- Tipo de imóvel com checkboxes -->
                    <div class="sem-filtros-avancados">
                        <div class="tipo-imovel">
                            <div class="tipo-imovel-btn">
                                <span>Tipo de imóvel</span>
                                <i class="fa-solid fa-chevron-down"></i>
                            </div>
                            <div>
                                <label>
                                    <input type="checkbox" id="tipo-todos"> Todos
                                </label>
                                <label>
                                    <input type="checkbox" name="tipo[]" value="casa"> Casa
                                </label>
                                <label>
                                    <input type="checkbox" name="tipo[]" value="apartamento"> Apartamento
                                </label>
                                <label>
                                    <input type="checkbox" name="tipo[]" value="terreno"> Terreno
                                </label>
                                <label>
                                    <input type="checkbox" name="tipo[]" value="comercial"> Comercial
                                </label>
                            </div>
                        </div>
                        <!-- Localização -->
                        <div class="input-group">
                            <label for="localizacao"></label>
                            <input type="text" name="localizacao" id="localizacao" placeholder="Cidade, bairro ou rua">
                        </div>
                        <!-- Botão para mostrar mais filtros -->
                        <button type="button" id="mais-filtros-btn">Mais filtros</button>
                        
                        <button class="submit-btn" type="submit">Procurar</button>
                    </div>

                    <!-- Filtros adicionais (inicialmente escondidos) -->
                    <div id="mais-filtros">
                        <div class="form-control option-control">
                            <label for="quartos">Quantidade de Quartos</label>
                            <select name="quartos" id="quartos">
                                <option value="">Qualquer</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4+</option>
                            </select>
                        </div>

                        <div class="form-control option-control">
                            <label for="banheiros">Quantidade de Banheiros</label>
                            <select name="banheiros" id="banheiros">
                                <option value="">Qualquer</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3+</option>
                            </select>
                        </div>

                        <div class="form-control option-control">
                            <label for="garagem">Vagas de garagem</label>
                            <select name="garagem" id="garagem">
                                <option value="">Qualquer</option>
                                <option value="0">0</option>
                                <option value="1">1+</option>
                                <option value="2">2+</option>
                                <option value="3">3+</option>
                            </select>
                        </div>

                        <div class="checkbox-container">
                            <div class="form-control">
                                <input type="checkbox" name="piscina" value="sim">
                                <label for="piscina">Piscina</label>
                            </div>
                            <div class="form-control">
                                <input type="checkbox" name="suite" id="suite" value="sim">
                                <label for="suite">Suíte</label>
                            </div>
                            <div class="form-control">
                                <input type="checkbox" name="mobilia" id="mobilia" value="sim">
                                <label for="mobilia">Mobiliado?</label>
                        </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
        <script src="assets/js/menu.js"></script>
        <script src="assets/js/fundo-animado.js"></script>
    </main>
        
    <?php include VIEWS . 'includes/footer.php';?>

</body>
</html>