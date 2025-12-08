<?php 

namespace Controllers;

use DAOs\MensagemDAO;

class MensagemController {
    private $mensagemDAO;

    public function __construct() {
        $this->mensagemDAO = new MensagemDAO();
    }

    public function index() {
        if(!isset($_SESSION['user_id']) || $_SESSION['user_id'] === null) {
            $_SESSION['error_message'] = "Você precisa estar logado para acessar suas mensagens.";
            header('Location: /');
            exit();
        }

        $userId = $_SESSION['user_id']; // Altere para o ID real do usuário logado
        $page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
        $limit = 5;
        $selectedId = isset($_GET['id']) ? (int)$_GET['id'] : null;

        // 1. Obter a lista paginada de mensagens
        $mensagens = $this->mensagemDAO->listPaginatedByDestinatario($userId, $page, $limit);
        
        // 2. Contar o total de mensagens e calcular o total de páginas
        $totalMensagens = $this->mensagemDAO->countTotalByDestinatario($userId);
        $totalPages = ceil($totalMensagens / $limit);

        // 3. Obter o detalhe da mensagem selecionada (e marcar como lida)
        $mensagemDetalhe = null;
        if ($selectedId) {
            $mensagemDetalhe = $this->mensagemDAO->findByIdAndDestinatario($selectedId, $userId);
        }

        // 4. Renderizar a view com todos os dados
        renderView('user/mensagens', [
            'mensagens' => $mensagens,
            'mensagemDetalhe' => $mensagemDetalhe,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalNaoLidas' => $this->mensagemDAO->countUnreadByDestinatario($userId)
        ]);
    }
}
?>