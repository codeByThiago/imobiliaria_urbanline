<?php 

namespace Controllers;

use Services\GoogleAuthService;
use Models\User;
use DAOs\UserDAO;
use Exception;

class AuthController {
    private UserDAO $userDAO;
    private GoogleAuthService $googleAuthService;

    public function __construct() {
        $this->userDAO = new UserDAO();
        $this->googleAuthService = new GoogleAuthService;
    }

    public function showLoginForm() {
        if(!isset($_SESSION['user_id'])) {
            require_once (VIEWS . 'user/login.php');
        } else {
            $_SESSION['error_message'] = "Você já está logado!";
            header('Location: /');
        }
    }

    public function login() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dataUser = [
                'name' => $_POST['nome'] ?? '',
                'password' => $_POST['senha'] ?? '',
                'email' => $_POST['email'] ?? '',
            ];

            $usuario = $this->userDAO->getByEmail($dataUser['email']);
            if(is_array($usuario)) {
                if($dataUser['email'] == $usuario['email'] && password_verify($dataUser['password'], $usuario['password'])) {
                    $_SESSION['logado'] = TRUE;
                    $_SESSION['user_id'] = $usuario['id'];
                    $_SESSION['username'] = $usuario['name'];
                    $_SESSION['sucess_message'] = 'Login realizado com sucesso!';
                    header('Location: /');
                } else {
                    $_SESSION['error_message'] = 'Email ou senha incorretos. Tente novamente!';
                    header('Location: /login');
                }
            } else {
                $_SESSION['error_message'] = 'Email ou senha incorretos. Tente novamente!';
                header('Location: /login');
            }
        }
    }

    public function showCadastroForm() {
        if(!isset($_SESSION['user_id'])) {
            require_once (VIEWS . 'user/cadastro.php');
        } else {
            $_SESSION['error_message'] = "Você já está logado! Por favor saia da conta caso queira cadastrar outro email.";
            header('Location: /');
        }
    }

    public function cadastro() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $dataUser = [
                'name' => $_POST['nome'] ?? '',
                'password' => $_POST['senha'] ?? '',
                'email' => $_POST['email'] ?? '',
            ];

            if ($_POST['senha'] !== $_POST['confirme-senha']) {
                $_SESSION['error_message'] = "As senhas não coincidem.";
                header('Location: /cadastro');
                exit;
            } else {
                $user = new User($dataUser);
                $newId = $this->userDAO->create($user->toArray());

                $user->setId($newId);

                $_SESSION['sucess_message'] = 'Seja bem vindo, ' . $dataUser['name'] . '. Você se cadastrou com sucesso!';
                header('Location: /login');
            }

        }
    }

    public function logout() : void {
        if(session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];

        session_unset();
        session_destroy();

        header('Location: /');
    }

    public function googleLogin() {
        header('Location: ' . $this->googleAuthService->getAuthUrl());
    }

    public function googleCallback() {
        try {
            if(!isset($_GET['code'])) {
                $_SESSION['error_message'] = 'Erro ao tentar autenticar com Google';
                header('Location: /login');
                exit;
            }

            $googleUser = $this->googleAuthService->getUserFromCode($_GET['code']);

            $user = $this->userDAO->getByEmail($googleUser['email']);

            if (!$user) {
                $this->userDAO->create(['google_id' => $googleUser['id'], 'auth_type' => 'google', 'name' => $googleUser['name'], 'email' => $googleUser['email'], 'picture' => $googleUser['picture']]);
            } else {
                $this->userDAO->update($user['id'], ['google_id' => $googleUser['id'], 'auth_type' => 'google', 'picture' => $googleUser['picture']]);
            }

            $user = $this->userDAO->getByEmail($googleUser['email']);
            $_SESSION['logado'] = TRUE;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['name'];
            $_SESSION['sucess_message'] = 'Login realizado com sucesso!';
            header('Location: /');

            // header('Location: /');

        } catch (Exception $e) {
            throw new Exception("Erro de Autenticação: " . $e->getMessage());
        }
    }

    public function showResetPasswordForm(): void {
        // Exibe o formulário de redefinição de senha (acessado via link do e-mail)
        if (isset($_GET['token'])) {
            $token = $_GET['token'];
            require_once VIEWS . 'user/reset_password.php';
        } else {
            $_SESSION['error_message'] = "Link inválido ou expirado.";
            header("Location: /login");
            exit;
        }
    }

    public function resetPassword(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tokenPuro = $_POST['token'] ?? '';
            $novaSenha = $_POST['senha'] ?? '';
            $confirmarSenha = $_POST['confirme-senha'] ?? '';

            if (empty($tokenPuro) || empty($novaSenha) || empty($confirmarSenha)) {
                $_SESSION['error_message'] = "Preencha todos os campos.";
                header("Location: /user/reset-password?token=" . urlencode($tokenPuro));
                exit;
            }

            if ($novaSenha !== $confirmarSenha) {
                $_SESSION['error_message'] = "As senhas não coincidem.";
                header("Location: /user/reset-password?token=" . urlencode($tokenPuro));
                exit;
            }

            $tokenHash = hash('sha256', $tokenPuro);

            // DAO que validará o token (PasswordResetsDAO)
            $passwordResetsDAO = new \DAOs\PasswordResetsDAO();
            $registro = $passwordResetsDAO->buscarPorToken($tokenHash);

            if (!$registro) {
                $_SESSION['error_message'] = "Token inválido ou expirado.";
                header("Location: /login");
                exit;
            }

            // Atualiza a senha do usuário
            $novaSenhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);

            try {
                $this->userDAO->atualizarSenha($registro['user_id'], $novaSenhaHash);
                $passwordResetsDAO->delete($registro['id']);

                $_SESSION['success_message'] = "Senha redefinida com sucesso! Faça login novamente.";
                header("Location: /login");
                exit;
            } catch (\Exception $e) {
                error_log("Erro ao redefinir senha: " . $e->getMessage());
                $_SESSION['error_message'] = "Erro ao redefinir senha. Tente novamente.";
                header("Location: /user/reset-password?token=" . urlencode($tokenPuro));
                exit;
            }
        } else {
            header("Location: /login");
            exit;
        }
    }
}

?>