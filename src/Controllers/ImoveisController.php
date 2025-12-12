<?php

namespace Controllers;

use DAOs\EnderecoDAO;
use DAOs\UserDAO;
use DAOs\ImoveisDAO;
use DAOs\ImovelFotosDAO;
use DAOs\MensagemDAO;
use Models\Imovel; 
use Models\ImovelFotos;
use Exception;

class ImoveisController {
    private ImoveisDAO $imoveisDAO;
    private ImovelFotosDAO $imovelFotosDAO;
    private EnderecoDAO $enderecoDAO;
    private UserDAO $userDAO;
    private MensagemDAO $mensagemDAO;
    private const ITEMS_PER_PAGE = 10;
    
    public function __construct() {
        $this->imoveisDAO = new ImoveisDAO();
        $this->imovelFotosDAO = new ImovelFotosDAO();
        $this->enderecoDAO = new EnderecoDAO();
        $this->userDAO = new UserDAO();
        $this->mensagemDAO = new MensagemDAO();
    }
    
    public function dashboard() {
        
        $userId = $_SESSION['user_id'];
        $totalImoveis = $this->imoveisDAO->countImoveisByUser($userId);

        $mensagensNaoLidas = $this->mensagemDAO->countUnreadByDestinatario($userId ?? null);
        
        $imoveisDisponiveis = $this->imoveisDAO->countImoveisByStatus($userId, 'disponivel');

        $imoveisCadastradosMes = $this->imoveisDAO->countImoveisCadastradosNoMes($userId);
        
        // 3. Renderiza a View
        renderView('user/dashboard', [
            'totalImoveis' => $totalImoveis,
            'totalMensagensNaoLidas' => $mensagensNaoLidas,
            'imoveisDisponiveis' => $imoveisDisponiveis,
            'imoveisCadastradosMes' => $imoveisCadastradosMes
        ]);
    }

    public function showCadastroImovelForm() {
        renderView('imovel/cadastro', [
            'totalMensagensNaoLidas' => $this->mensagemDAO->countUnreadByDestinatario($_SESSION['user_id'] ?? null)
        ]);
    }
    
    public function imoveisCrud() {
        renderView('imovel/imoveis', [
            'imoveis' => $this->imoveisDAO->listAllById($_SESSION['user_id']),
            'totalMensagensNaoLidas' => $this->mensagemDAO->countUnreadByDestinatario($_SESSION['user_id'] ?? null)
        ]);
    }

    public function cadastrarImovel() {
       
        $fotos_urls = [];
        try {
            $fotos_urls = $this->handleFileUploads($_FILES['fotos'] ?? null);

            $user = $this->userDAO->selectById($_SESSION['user_id']);

            $dadosImovel = [
                'usuario_id'    => $_SESSION['user_id'],
                'nome'          => $user['nome'],
                'tipo_imovel'   => $_POST['tipo_imovel'],
                'condicao'      => $_POST['condicao'],
                'valor'         => (float) str_replace(',', '.', $_POST['valor']), 
                'area'          => (float) str_replace(',', '.', $_POST['area']),
                
                'quant_quartos' => (int) ($_POST['quant_quartos'] ?? 0),
                'quant_suites'  => (int) ($_POST['quant_suites'] ?? 0),
                'quant_banheiros' => (int) ($_POST['quant_banheiros'] ?? 0),
                'vagas_garagem' => (int) ($_POST['vagas_garagem'] ?? 0),
                'quant_piscinas' => (int) ($_POST['quant_piscinas'] ?? 0),
                'mobiliado'     => (int) ($_POST['mobiliado'] ?? 0),
                'descricao'     => $_POST['descricao'],
                'status'        => 'disponivel', // Status inicial
            ];

            $dadosEndereco = [
                'cep'           => $_POST['cep'],
                'logradouro'    => $_POST['logradouro'],
                'numero'        => $_POST['numero'],
                'bairro'        => $_POST['bairro'],
                'cidade'        => $_POST['cidade'],
                'uf'            => $_POST['uf']
            ];

            $enderecoId = $this->enderecoDAO->create($dadosEndereco);

            $dadosImovel['endereco_id'] = $enderecoId;

            $imovelId = $this->imoveisDAO->create($dadosImovel); 

            if (!$imovelId) {
                throw new Exception("Falha ao inserir o imóvel principal no banco.");
            }

            if (!empty($fotos_urls)) {
                $this->imoveisDAO->salvarFotos($imovelId, $fotos_urls);
            }

            $_SESSION['success_message'] = 'Imóvel cadastrado com sucesso! ID: ' . $imovelId;
            header('Location: /dashboard'); 
            exit;

        } catch (Exception $e) {
            throw new Exception("Erro ao cadastrar imóvel " . $e->getMessage());
        }
    }

    // Arquivo: ImoveisController.php

    public function deletaImovel() {
        
        // 1. Verificação de Autenticação e ID
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error_message'] = 'Acesso negado. Você precisa estar logado.';
            header('Location: /login');
            exit;
        }

        // 2. Obter o ID do imóvel da URL (GET)
        $imovelId = $_GET['id'] ?? null;
        
        if (!$imovelId || !is_numeric($imovelId)) {
            $_SESSION['error_message'] = 'ID do imóvel inválido.';
            header('Location: /imoveis-proprietario');
            exit;
        }

        $userId = $_SESSION['user_id'];
        
        // Início da Lógica de Exclusão
        try {
            
            // --- 3. VERIFICAÇÃO DE PROPRIEDADE (SEGURANÇA CRÍTICA!) ---
            
            // Presumo que você tenha um método no ImoveisDAO para buscar o imóvel pelo ID.
            $imovel = $this->imoveisDAO->selectById($imovelId); 

            if (!$imovel) {
                throw new Exception("Imóvel não encontrado.");
            }

            // Garante que o usuário logado é o proprietário real do imóvel
            if ($imovel['usuario_id'] != $userId) {
                // Este é um ataque de acesso não autorizado, deve ser rigoroso
                throw new Exception("Você não tem permissão para deletar este imóvel.");
            }
            
            $this->imoveisDAO->delete($imovelId);     // Deleta o imóvel
            
            // Deleta o endereço associado
            $this->enderecoDAO->delete($imovel['endereco_id']); 
            
            // 6. SUCESSO
            $_SESSION['success_message'] = 'Imóvel deletado com sucesso.';
            header('Location: /imoveis-proprietario');
            exit;
            
        } catch (Exception $e) {
            error_log("Erro ao deletar imóvel (ID {$imovelId}): " . $e->getMessage());
            $_SESSION['error_message'] = "Erro ao deletar imóvel: " . $e->getMessage();
            header('Location: /imoveis-proprietario');
            exit;
        }
    }

    private function handleFileUploads(?array $files): array {
        $urls = [];

        if (!$files || empty($files['name'][0])) {
            return $urls;
        }

        $total = count($files['name']);
        
        // Caminho ABSOLUTO no SERVIDOR (onde o arquivo será salvo)
        // BASE_DIR + public/uploads/imoveis/
        $uploadPath = BASE_DIR . "public" . DIRECTORY_SEPARATOR . "assets/" . DIRECTORY_SEPARATOR . "img" . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . "imoveis" . DIRECTORY_SEPARATOR; 
        
        // Caminho relativo para salvar no banco (para ser acessado pelo navegador)
        $public_url_base = "/assets/img/uploads/imoveis/";


        // Garantir que a pasta existe (com permissão 0777 em ambiente de desenvolvimento)
        if (!is_dir($uploadPath)) {
            // Tenta criar o diretório recursivamente
            if (!mkdir($uploadPath, 0777, true)) {
                throw new Exception("Falha ao criar o diretório de upload: " . $uploadPath);
            }
        }

        for ($i = 0; $i < $total; $i++) {
            if ($files['error'][$i] === UPLOAD_ERR_OK) {

                $tempPath = $files['tmp_name'][$i];
                $extension = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
                $fileName  = uniqid("imovel_") . "." . $extension;

                $targetPath = $uploadPath . $fileName;

                // Tenta mover o arquivo
                if (move_uploaded_file($tempPath, $targetPath)) {
                    // Salva no array a URL pública (para ser usado no HTML, no banco de dados)
                    $urls[] = $public_url_base . $fileName;
                } else {
                    // Se move_uploaded_file falhar, o motivo pode ser permissão (0777)
                    throw new Exception("Falha ao mover o arquivo. Verifique permissões (chmod) na pasta: " . $uploadPath);
                }

            } else if ($files['error'][$i] !== UPLOAD_ERR_NO_FILE) {
                // Este bloco captura UPLOAD_ERR_INI_SIZE (Código 1) ou outros
                throw new Exception("Erro no upload do arquivo (Código: {$files['error'][$i]}).");
            }
        }

        return $urls;
    }
    
    public function search() {
        // 1. Coleta a página atual (default 1) e calcula o OFFSET
        $page = (int) ($_GET['page'] ?? 1);
        if ($page < 1) $page = 1;
        
        $offset = ($page - 1) * self::ITEMS_PER_PAGE;
        
        // 2. Coleta e sanitiza os filtros de $_GET
        $filters = $this->sanitizeFilters($_GET);
        
        // 3. Chama o NOVO método do DAO com os filtros, limite e offset
        // Este método retorna: ['imoveis' => [...], 'fotos_por_imovel' => [...], 'total_imoveis' => X]
        $results = $this->imoveisDAO->findWithPrimaryPhoto($filters, self::ITEMS_PER_PAGE, $offset);
        
        $imoveis = $results['imoveis'] ?? [];
        $fotos_por_imovel = $results['fotos_por_imovel'] ?? [];
        $total_imoveis = $results['total_imoveis'] ?? 0; // Novo
        
        // 4. Calcula o total de páginas
        $total_pages = ceil($total_imoveis / self::ITEMS_PER_PAGE);

        $totalMensagensNaoLidas = 0;
        if (isset($_SESSION['logado']) && $_SESSION['logado'] === TRUE) {
            // Faça a chamada DAO no Controller e guarde o resultado
            $totalMensagensNaoLidas = $this->mensagemDAO->countUnreadByDestinatario($_SESSION['user_id'] ?? 0);
        }

        // 5. Renderiza a View
        renderView('imovel/procura-imoveis', [
            'imoveis' => $imoveis, 
            'fotos_por_imovel' => $fotos_por_imovel,
            'filters' => $filters,
            'currentPage' => $page, // Novo
            'totalPages' => $total_pages, // Novo
            'totalMensagensNaoLidas' => $totalMensagensNaoLidas
        ]);
    }

    private function sanitizeFilters(array $input): array {
        $safeFilters = [];
        
        // Filtros de Status e Tipo (STRING)
        $safeFilters['status-imovel'] = filter_var($input['status-imovel'] ?? '');
        $safeFilters['tipo'] = filter_var($input['tipo'] ?? '');
        $safeFilters['valor'] = filter_var($input['valor'] ?? '');
        $safeFilters['ordenar-por'] = filter_var($input['ordenar-por'] ?? '');
        
        // Filtros Numéricos (INT ou STRING para '4+')
        $safeFilters['quartos'] = $this->sanitizeQuantity($input['quartos'] ?? null);
        $safeFilters['banheiros'] = $this->sanitizeQuantity($input['banheiros'] ?? null);
        $safeFilters['cozinhas'] = $this->sanitizeQuantity($input['cozinhas'] ?? null);
        $safeFilters['piscinas'] = $this->sanitizeQuantity($input['piscinas'] ?? null);
        $safeFilters['vagas-de-garagem'] = $this->sanitizeQuantity($input['vagas-de-garagem'] ?? null);
        
        $mobiliado = $input['mobiliado'] ?? null;
        if ($mobiliado !== null && $mobiliado !== '') {
            // Garante que é 1 (true) ou 0 (false)
            $safeFilters['mobiliado'] = filter_var($mobiliado, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => 1]]);
            if ($safeFilters['mobiliado'] === false) {
                 // Trata como null se não for 0 ou 1
                $safeFilters['mobiliado'] = null; 
            }
        }
        
        // Adicione esta função auxiliar:
        $safeFilters['search-input'] = filter_var($input['search-input'] ?? '');

        // Remove valores vazios para não poluir o DAO com binds desnecessários
        return array_filter($safeFilters, fn($value) => $value !== null && $value !== '');
    }

    private function sanitizeQuantity($value): string|int|null {
        if (in_array($value, ['4+', '3+', '2+'])) {
            return $value; // Mantém a string especial
        }
        return filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]) !== false
            ? (int) $value
            : null;
    }

    public function detalheImovel() {
        $imovelID = $_GET['id'] ?? null;
        if ($imovelID === null) {
            // Redireciona ou mostra erro se o ID não for fornecido
            header('Location: /search');
            exit;
        }
        
        // Aqui você pode buscar os detalhes do imóvel usando o ID
        $imovel = $this->imoveisDAO->selectById($imovelID);
        
        if (!$imovel) {
            // Se o imóvel não for encontrado, redirecione ou mostre um erro
            header('Location: /search');
            exit;
        }
        
        // Buscar o endereço do imóvel
        $endereco = $this->enderecoDAO->selectById($imovel['endereco_id']);

        // Buscar fotos do imóvel
        $fotos = $this->imovelFotosDAO->findByImovelId($imovelID);

        // Buscar pelo proprietário
        $proprietario = $this->userDAO->selectById($imovel['usuario_id']);
        
        $totalMensagensNaoLidas = 0;
        if (isset($_SESSION['logado']) && $_SESSION['logado'] === TRUE) {
            // Faça a chamada DAO no Controller e guarde o resultado
            $totalMensagensNaoLidas = $this->mensagemDAO->countUnreadByDestinatario($_SESSION['user_id'] ?? 0);
        }

         // Renderizar a view com os detalhes do imóvel e suas fotos
        renderView('imovel/detalhe-imovel', [
            'imovel' => $imovel,
            'fotos' => $fotos,
            'endereco' => $endereco,
            'proprietario' => $proprietario,
            'totalMensagensNaoLidas' => $totalMensagensNaoLidas
        ]);
    }

    public function contatoCorretorForm() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error_message'] = "Você precisa estar logado para enviar uma mensagem ao corretor.";
            header('Location: /detalhe-imovel?id=' . ($_GET['imovel'] ?? ''));
            exit();
        }

        $imovelId = $_GET['imovel'] ?? null;
        $corretorId = $_GET['corretor'] ?? null;

        $imovel = $this->imoveisDAO->selectById($imovelId);
        $corretor = $this->userDAO->selectById($corretorId);
        
        // Inicializa variáveis para o formulário
        $data = [
            'imovel' => $imovel,
            'corretor' => $corretor,
            'remetente_nome' => null,
            'remetente_telefone' => null,
            'remetente_email' => null,
        ];

        // 1. Verificar se o corretor é válido para o imóvel
        if($this->imoveisDAO->verificarProprietario($imovelId, $corretorId)) {
            
            // 2. Verificar se o usuário está logado
            if(isset($_SESSION['user_id'])) {
                $userId = $_SESSION['user_id'];

                $remetente = $this->userDAO->selectById($userId);

                if ($remetente !== null) {
                    $data['remetente_nome'] = $remetente['nome'];
                    $data['remetente_telefone'] = $remetente['telefone'];
                    $data['remetente_email'] = $remetente['email'];
                }
            }

        $totalMensagensNaoLidas = 0;
        if (isset($_SESSION['logado']) && $_SESSION['logado'] === TRUE) {
            // Faça a chamada DAO no Controller e guarde o resultado
            $totalMensagensNaoLidas = $this->mensagemDAO->countUnreadByDestinatario($_SESSION['user_id'] ?? 0);
        }

        $data['totalMensagensNaoLidas'] = $totalMensagensNaoLidas;

        renderView('user/contato/corretor', $data);
            
            
        } else {
            $_SESSION['error_message'] = "Corretor inválido para o imóvel selecionado.";
            header('Location: /search');
            exit();
        }
    }

    public function enviarMensagemCorretor() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error_message'] = "Você precisa estar logado para enviar uma mensagem ao corretor.";
            header('Location: /detalhe-imovel?id=' . ($_POST['imovel_id'] ?? ''));
            exit();
        }

        $userId = $_SESSION['user_id'];

        $destinatario_id = $_POST['corretor_id'] ?? null;
        $assunto = $_POST['assunto'] ?? '';
        $mensagem = $_POST['mensagem'] ?? '';
        $imovelId = $_POST['imovel_id'] ?? null;
        
        $data = [
            'destinatario_id' => $destinatario_id,
            'remetente_id' => $userId,
            'titulo' => $assunto,
            'mensagem' => $mensagem,
            'link' => '/detalhe-imovel?id=' . $imovelId,
        ];

        try {
            $this->mensagemDAO->create($data);
            
            $_SESSION['success_message'] = "Mensagem enviada com sucesso ao corretor!";
            header('Location: /detalhe-imovel?id=' . $imovelId); 
            exit();

        } catch (\PDOException $e) {
            $_SESSION['error_message'] = "Erro ao enviar a mensagem. Tente novamente.";
            error_log("Erro ao enviar mensagem: " . $e->getMessage());
            header('Location: /contato-corretor-form?imovel=' . $imovelId . '&corretor=' . $destinatario_id);
            exit();
        }
    }
}