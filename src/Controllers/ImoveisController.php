<?php

namespace Controllers;

use DAOs\EnderecoDAO;
use DAOs\UserDAO;
use DAOs\ImoveisDAO;
use DAOs\ImovelFotosDAO;
use DAOs\MensagemDAO;
use Models\Imovel; 
use Models\ImovelFotos;

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

        // 5. Renderiza a View
        renderView('imovel/procura-imoveis', [
            'imoveis' => $imoveis, 
            'fotos_por_imovel' => $fotos_por_imovel,
            'filters' => $filters,
            'currentPage' => $page, // Novo
            'totalPages' => $total_pages // Novo
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

    // Função auxiliar (pode ser adicionada dentro da classe ou como método privado)
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
         
         // Renderizar a view com os detalhes do imóvel e suas fotos
         renderView('imovel/detalhe-imovel', [
             'imovel' => $imovel,
             'fotos' => $fotos,
             'endereco' => $endereco,
             'proprietario' => $proprietario
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
            'remetente_email' => null
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