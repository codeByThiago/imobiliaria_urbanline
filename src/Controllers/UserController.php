<?php

namespace Controllers;

use Models\User;
use Models\Mensagem;
use DAOs\UserDAO;
use DAOs\MensagemDAO;
use DAOs\EnderecoDAO;
use Exception;

// --- Helper de View (Simulação) ---
function renderView(string $viewName, array $data = []): void {
    extract($data);
    
    $viewName = VIEWS . str_replace('\\', DIRECTORY_SEPARATOR, $viewName) . ".php";

    if (file_exists($viewName)) {
        require $viewName;
    } else {
        return;
    }
}

class UserController {
    private UserDAO $userDAO;
    private MensagemDAO $mensagemDAO;
    private EnderecoDAO $enderecoDAO;
    
    public function __construct() {
        $this->userDAO = new UserDAO();
        $this->mensagemDAO = new MensagemDAO();
        $this->enderecoDAO = new EnderecoDAO();
    }

    
    public function index() {
        
        renderView('user/home', ['totalMensagensNaoLidas' => $this->mensagemDAO->countUnreadByDestinatario($_SESSION['user_id'] ?? 0)]);
    }

    public function perfil() {
        $user = $this->userDAO->selectById($_SESSION['user_id'] ?? 0);
        $endereco = null;
        $canManageImoveis = false;

        if ($user) {
            $endereco = $this->enderecoDAO->selectById($user['endereco_id'] ?? 0);
        }

        if ($user && in_array($user['role_id'], [2, 3])) {
            $canManageImoveis = true;
        }

        renderView('user/perfil', [
            'user' => $user ?? null, 
            'endereco' => $endereco ?? null,
            'canManageImoveis' => $canManageImoveis,
            'totalMensagensNaoLidas' => $this->mensagemDAO->countUnreadByDestinatario($_SESSION['user_id'] ?? 0)
        ]);
    }

    public function showContatoForm() {
        $user = $this->userDAO->selectById($_SESSION['user_id']);
        
        renderView('user/contato/urbanline', [
            'user' => $user ?? null,
            'totalMensagensNaoLidas' => $this->mensagemDAO->countUnreadByDestinatario($_SESSION['user_id'] ?? 0)
        ]);
    }

    public function enviarMensagemContato() {
        $data = [
            'remetente_nome' => $_POST['nome'] ?? '',
            'remetente_email' => $_POST['email'] ?? '',
            'destinatario_id' => 11,
            'titulo' => $_POST['titulo'] ?? '',
            'mensagem' => $_POST['mensagem'] ?? ''
        ];

        if (isset($_SESSION['user_id'])) {
            $data['remetente_id'] = $_SESSION['user_id'];
        }

        $this->mensagemDAO->create($data);

        $_SESSION['success_message'] = 'Mensagem enviada com sucesso!';

        header('Location: /contato');
        exit();
    }

    public function errorPage404() {
        renderView('user/404');
    }
}