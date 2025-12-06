<?php

namespace Controllers;

use DAOs\UsuarioDAO;
// use Services\GoogleAuthService;
use Exception;

class AuthController {
    private UsuarioDAO $usuarioDAO;
    // private GoogleAuthService $googleService;

    public function __construct() {
        $this->usuarioDAO = new UsuarioDAO();
        // $this->googleService = new GoogleAuthService();
    }

    public function showLoginForm(): void {
        // $googleAuthUrl = $this->googleService->getAuthUrl();
        renderView('auth/login', ['googleAuthUrl' => '/cadastro']);
    }
    
    public function login(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /login');
            return;
        }

        $email = $_POST['email'] ?? '';
        $senhaDigitada = $_POST['senha'] ?? '';

        if (empty($email) || empty($senhaDigitada)) {
            $_SESSION['error_message'] = "Preencha todos os campos.";
            header('Location: /login');
            return;
        }

        try {
            $usuario = $this->usuarioDAO->selectByEmail($email);

            if ($usuario && $usuario->verificarSenha($senhaDigitada)) {
                // Login bem-sucedido: Cria a sessão do usuário
                $_SESSION['user_id'] = $usuario->getId();
                $_SESSION['user_nome'] = $usuario->getNome();
                $_SESSION['success_message'] = "Login efetuado com sucesso!";
                
                header('Location: /'); 

            } else {
                // Falha no login
                $_SESSION['error_message'] = "Email ou senha incorretos.";
                header('Location: /login');
            }

        } catch (Exception $e) {
            $_SESSION['error_message'] = "Ocorreu um erro interno. Tente novamente.";
            header('Location: /login');
        }
    }

    public function logout(): void {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();
    }
        $_SESSION['success_message'] = "Sessão encerrada com sucesso!";
        header('Location: /'); 
    }
}