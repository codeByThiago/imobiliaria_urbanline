<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/412e60f1e0.js" crossorigin="anonymous"></script>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/crud.css">
    <title>Urbanline Imóveis - Início</title>
</head>
<body>
<main>
    <section id="adiciona-imovel">
        <div>
            <button id="novo-imovel-btn">Novo Imóvel</button>
            <div class="input-wrapper">
                <label for="buscar" class="sr-only">Buscar Imóvel</label>
                <input type="text" name="buscar" id="buscar" placeholder="Buscar...">
            </div>
        </div>
        <div id="lista-imovel">
            <table>
                <thead> 
                    <tr>
                        <th>Foto</th>
                        <th>Tipo</th>
                        <th>Valor</th>
                        <th>Área</th>
                        <th>Quartos</th>
                        <th>Cozinhas</th>
                        <th>Banheiros</th>
                        <th>Piscinas</th>
                        <th>Garagem</th>
                        <th>Status</th>
                        <th>Deletar</th>
                        <th>Editar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    
                    include '../src/includes/conexao.php';

                    $sql_query_imoveis = 'SELECT * FROM imoveis';

                    $stmt_imoveis = $conn->prepare($sql_query_imoveis);
                    $stmt_imoveis->execute();

                    $imoveis[] = $stmt_imoveis->fetchAll(PDO::FETCH_ASSOC);
                    
                    foreach ($imoveis as $imovel) {?>
                        <tr>
                        <?php foreach ($fotos_por_imovel[$imovel['id']] as $foto_url): ?>
                            <img src="../../public/assets/img/thumbs/<?= htmlspecialchars($foto_url) ?>" alt="Foto do imóvel" loading="lazy">
                            <?php endforeach; ?>
                            <td><?= $imovel['tipo_imovel']?></td>
                            <td><?= number_format($imovel['valor'], 2, ',', '.') ?></td>
                            <td><?= $imovel['area'] ?> m²</td>
                            <td><?= $imovel['quant_quartos'] ?></td>
                            <td><?= $imovel['quant_suites'] ?></td>
                            <td><?= $imovel['quant_cozinhas'] ?></td>
                            <sptdan><?= $imovel['quant_piscinas'] ?></td>
                            <td><?= $imovel['vagas_garagem'] ?></td>
                            <td class="disponivel">Disponível</td>
                            <td><i class="fa-solid fa-trash"></i></td>
                            <td><i class="fa-solid fa-pen"></i></td>
                        </tr>
                    <?php }?>
                </tbody>
            </table>
        </div>
        <form action="adiciona-imovel.php" method="get" id="novo-imovel">
            <h2>Novo Imóvel</h2>
            <div class="input-group">
                <label for="tipo">Tipo</label>
                <select name="tipo" id="tipo">
                    <option selected value="casa">Casa</option>
                    <option value="apartamento">Apartamento</option>
                    <option value="kitnet">Kitnet</option>
                    <option value="sobrado">Sobrado</option>
                    <option value="terreno">Terreno</option>
                    <option value="comercial">Comercial</option>
                </select>
            </div>
            <div class="form-control">
                <label for="valor">Valor</label>
                <input type="number" name="valor" id="valor" min="1">
            </div>
            <div class="input-group">
                <label for="status">Status</label>
                <select name="status" id="status">
                    <option value="disponivel">Disponível</option>
                    <option value="alugado">Alugado</option>
                    <option value="em_construcao">Em construção</option>
                </select>
            </div>
            <div class="input-group" id="descricao">
                <label for="descricao">Descrição</label>
                <textarea name="descricao" id="descricao"></textarea>
            </div>
            <div class="form-control">
                <label for="quartos">Quartos</label>
                <input type="number" name="quartos" id="quartos">
            </div>
            <div class="form-control">
                <label for="Banheiros">Banheiros</label>
                <input type="number" name="banheiros" id="banheiros">
            </div>
            <div class="form-control">
                <label for="cozinhas">Cozinhas</label>
                <input type="number" name="cozinhas" id="cozinhas">
            </div>
            <div class="form-control">
                <label for="piscinas">Piscinas</label>
                <input type="number" name="piscinas" id="piscinas">
            </div>
            <div class="form-control">
                <label for="vagas-de-garagem">Vagas de Garagem</label>
                <input type="number" name="quartos" id="vagas-de-garagem">
            </div>
            <div class="form-control file-upload">
                <label for="imagens" class="custom-file-label">Escolher imagens</label>
                <input type="file" name="imagens[]" id="imagens" accept="image/*" multiple>
                <span id="file-chosen">Nenhum arquivo escolhido</span>
            </div>
            <button type="submit" id="save-imovel-btn">Salvar</button>
        </form>
    </section>
</main>