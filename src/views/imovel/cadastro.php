<?php
// O Controller deve garantir que o usuário está logado e tem permissão 'cadastrar_imovel'
// Caso contrário, deve redirecionar.
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Imóvel - UrbanLine</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/cadastro-imovel.css"> <script src="https://kit.fontawesome.com/412e60f1e0.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php include VIEWS . 'includes/navbar.php';?>
    <?php include VIEWS . 'includes/alert.php';?>

    <main class="form-page-container">
        <section class="form-section cadastro-imovel">
            <h1 class="form-title">Cadastrar Novo Imóvel</h1>
            <p class="form-subtitle">Preencha os dados em 3 passos simples.</p>

            <div class="progress-bar-container">
                <div class="progress-step active" id="step-1-indicator">1. Detalhes</div>
                <div class="progress-step" id="step-2-indicator">2. Características</div>
                <div class="progress-step" id="step-3-indicator">3. Localização</div>
            </div>

            <form action="" method="POST" enctype="multipart/form-data" id="imovel-form">
                <input type="hidden" name="usuario_id" value="<?php echo $_SESSION['user_id'] ?? ''; ?>">
                
                <div class="form-step active" data-step="1">
                    <h2 class="step-title">1. Informações Essenciais</h2>

                    <div class="form-control">
                        <label for="nome">Título do Anúncio *</label>
                        <input type="text" id="nome" name="nome" required maxlength="100">
                        <p class="help-text">Ex: Apartamento Moderno no Centro ou Casa com Piscina em Alphaville.</p>
                    </div>

                    <div class="form-control">
                        <label for="tipo_imovel">Tipo de Imóvel *</label>
                        <select name="tipo_imovel" id="tipo_imovel" required>
                            <option value="" disabled selected>Selecione</option>
                            <option value="casa">Casa</option>
                            <option value="apartamento">Apartamento</option>
                            <option value="kitnet">Kitnet</option>
                            <option value="sobrado">Sobrado</option>
                            <option value="terreno">Terreno</option>
                            <option value="comercial">Comercial</option>
                            <option value="cobertura">Cobertura</option>
                            <option value="galpao">Galpão</option>
                            <option value="chacara">Chácara</option>
                        </select>
                    </div>

                    <div class="form-control">
                        <label for="condicao">Condição *</label>
                        <select name="condicao" id="condicao" required>
                            <option value="" disabled selected>Selecione</option>
                            <option value="novo">Novo</option>
                            <option value="usado">Usado</option>
                            <option value="em_construcao">Em Construção</option>
                        </select>
                    </div>
                    
                    <div class="form-control">
                        <label for="valor">Valor (R$) *</label>
                        <input type="number" id="valor" name="valor" step="0.01" min="0" required>
                        <p class="help-text">Use apenas números. Ex: 250000.00</p>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="next-step-btn" data-next-step="2">Próximo Passo</button>
                    </div>
                </div> 
                
                <div class="form-step" data-step="2">
                    <h2 class="step-title">2. Características e Recursos</h2>

                    <div class="form-control">
                        <label for="area">Área Total (m²) *</label>
                        <input type="number" id="area" name="area" step="0.01" min="1" required>
                    </div>

                    <div class="form-group-inline">
                        <div class="form-control">
                            <label for="quant_quartos">Quartos</label>
                            <input type="number" id="quant_quartos" name="quant_quartos" min="0" value="0">
                        </div>
                        <div class="form-control">
                            <label for="quant_suites">Suítes</label>
                            <input type="number" id="quant_suites" name="quant_suites" min="0" value="0">
                        </div>
                        <div class="form-control">
                            <label for="quant_banheiros">Banheiros</label>
                            <input type="number" id="quant_banheiros" name="quant_banheiros" min="0" value="0">
                        </div>
                        <div class="form-control">
                            <label for="vagas_garagem">Vagas de Garagem</label>
                            <input type="number" id="vagas_garagem" name="vagas_garagem" min="0" value="0">
                        </div>
                    </div>

                    <div class="form-group-inline">
                        <div class="form-control">
                            <label for="quant_piscinas">Piscinas (Quantidade)</label>
                            <input type="number" id="quant_piscinas" name="quant_piscinas" min="0" value="0">
                        </div>
                        <div class="form-control">
                            <label for="mobiliado">Mobiliado?</label>
                            <select name="mobiliado" id="mobiliado">
                                <option value="0" selected>Não</option>
                                <option value="1">Sim</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-control">
                        <label for="descricao">Descrição Completa</label>
                        <textarea id="descricao" name="descricao" rows="5" maxlength="1000"></textarea>
                        <p class="help-text">Destaque os pontos fortes e diferenciais do imóvel.</p>
                    </div>

                    <div class="form-control">
                        <label for="fotos[]">Fotos do Imóvel (Múltipla Seleção) *</label>
                        <input type="file" id="fotos" name="fotos[]" accept="image/*" multiple required>
                        <p class="help-text">Selecione fotos de alta qualidade do imóvel.</p>
                    </div>


                    <div class="form-actions">
                        <button type="button" class="prev-step-btn" data-prev-step="1">Voltar</button>
                        <button type="button" class="next-step-btn" data-next-step="3">Próximo Passo</button>
                    </div>
                </div>
                
                <div class="form-step" data-step="3">
                    <h2 class="step-title">3. Localização</h2>

                    <div class="form-control">
                        <label for="cep">CEP *</label>
                        <input type="text" id="cep" name="cep" required maxlength="10">
                        <p class="help-text">O preenchimento do CEP pode preencher outros campos automaticamente.</p>
                    </div>
                    
                    <div class="form-group-inline">
                        <div class="form-control" style="flex-grow: 3;">
                            <label for="logradouro">Rua/Logradouro *</label>
                            <input type="text" id="logradouro" name="logradouro" required maxlength="100">
                        </div>
                        <div class="form-control" style="flex-grow: 1;">
                            <label for="numero">Número</label>
                            <input type="text" id="numero" name="numero" maxlength="15">
                        </div>
                    </div>

                    <div class="form-control">
                        <label for="bairro">Bairro *</label>
                        <input type="text" id="bairro" name="bairro" required maxlength="100">
                    </div>

                    <div class="form-group-inline">
                        <div class="form-control">
                            <label for="cidade">Cidade *</label>
                            <input type="text" id="cidade" name="cidade" required maxlength="100">
                        </div>
                        <div class="form-control">
                            <label for="uf">Estado (UF) *</label>
                            <input type="text" id="uf" name="uf" required maxlength="2">
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="prev-step-btn" data-prev-step="2">Voltar</button>
                        <button type="submit" class="auth-submit-btn">Finalizar Cadastro</button>
                    </div>
                </div>

            </form>
        </section>
    </main>

    <?php include VIEWS . 'includes/footer.php';?>
    <script src="assets/js/multi-step-form.js"></script>
    <script type="module" src="assets/js/cep.js"></script>
</body>
</html>