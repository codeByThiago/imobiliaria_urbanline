<?php

namespace Controllers;

use Models\Usuario;
use DAOs\UsuarioDAO;
use Models\Endereco;
use DAOs\EnderecoDAO;
use Exception;

// --- Helper de View (Simulação) ---
function renderView(string $viewName, array $data = []): void {
    extract($data);

    // Exibe mensagens de sucesso/erro da sessão
    if (isset($_SESSION['success_message'])) {
        unset($_SESSION['success_message']);
    }
    if (isset($_SESSION['error_message'])) {
        unset($_SESSION['error_message']);
    }

    $viewName = VIEWS . str_replace('\\', DIRECTORY_SEPARATOR, $viewName) . ".php";

    if (file_exists($viewName)) {
        require $viewName;
    } else {
        return;
    }
}

class UsuarioController {
    private EnderecoDAO $enderecoDAO;
    private UsuarioDAO $usuarioDAO;

    public function __construct() {
        $this->usuarioDAO = new UsuarioDAO();
        $this->enderecoDAO = new EnderecoDAO();
    }

    
    public function index() {
        renderView('user/home');
    }
    
    public function errorPage404() {
        renderView('user/404');
    }
    
    public function showRegisterForm() : void {
        renderView('auth/cadastro');
    }

    public function register(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /cadastro');
            return;
        }
        
        try {
            $data = $_POST;

            $enderecoData = [
                'cep' => $data['cep'] ?? '',
                'uf' => $data['uf'] ?? '',
                'cidade' => $data['cidade'] ?? '',
                'bairro' => $data['bairro'] ?? '',
                'logradouro' => $data['logradouro'] ?? '',
                'numero' => $data['numero'] ?? '',
            ];

            $endereco = new Endereco($enderecoData);
            $enderecoId = $this->enderecoDAO->create($endereco);

            $userData = [
                'nome' => $data['nome'] ?? '',
                'email' => $data['email'] ?? '',
                'senha' => $data['senha'] ?? '', 
                'telefone' => $data['telefone'] ?? '',
                'cpf' => $data['cpf'] ?? '',
                'role_id' => $data['role_id'] ?? 1,
                'endereco_id' => $enderecoId ?? '',
                ];


            $usuario = new Usuario($userData);
            if (!empty($data['senha'])) {
                $usuario->setSenha($data['senha']);
            }

            $userId = $this->usuarioDAO->create($usuario);
            
            $_SESSION['success_message'] = "Usuário cadastrado com sucesso! ID: {$userId}";
            header('Location: /');
            
        } catch (Exception $e) {
            $_SESSION['error_message'] = "Erro ao cadastrar usuário: " . $e->getMessage();
            header('Location: /cadastro'); 
        }
    }
}