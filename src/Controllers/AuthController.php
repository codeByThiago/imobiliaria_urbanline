<?php 

namespace Controllers;

use DAOs\EnderecoDAO;
use Services\GoogleAuthService;
use Models\User;
use DAOs\UserDAO;
use Exception;

class AuthController {
    private UserDAO $userDAO;
    private EnderecoDAO $enderecoDAO;
    private GoogleAuthService $googleAuthService;

    public function __construct() {
        $this->userDAO = new UserDAO();
        $this->enderecoDAO = new EnderecoDAO();
        $this->googleAuthService = new GoogleAuthService;
    }

    public function showLoginForm() : void {
        renderView('auth/login');
    }

    public function login() : void {

        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';

        if (empty($email) || empty($senha)) {
            $_SESSION['error_message'] = 'Por favor, preencha todos os campos.';
            header('Location: /login');
            exit;
        }
        
        $user = $this->userDAO->getByEmail($email);
        
        if ($user && password_verify($senha, $user['senha'])) {
            $_SESSION['logado'] = TRUE;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['nome'];
            $_SESSION['user_picture'] = 'assets/uploads/user/' . $user['picture'] ?? null;
            $_SESSION['sucess_message'] = 'Login realizado com sucesso!';
            header('Location: /');
            exit;
        } else {
            $_SESSION['erro_message'] = 'Email ou senha inválidos.';
            header('Location: /login');
            exit;
        }
    }

    public function logout() : void {
        session_unset();
        session_destroy();
        header('Location: /');
        exit;
    }

    public function showCadastroForm() : void {
        renderView('auth/cadastro');
    }

    public function cadastro() : void {
        
        // 1. Lógica de Validação de Email (mantida)
        $email = $this->userDAO->getByEmail($_POST['email'] ?? '');
        if ($email && ($_POST['auth_type'] ?? 'local') === 'local') { 
            // Se o email existe E o tipo de auth é local, bloqueia.
            $_SESSION['error_message'] = 'Email já cadastrado.';
            header('Location: /cadastro');
            exit;
        }
        
        // 2. Coleta de dados (Incluindo os campos ocultos do Google, se existirem)
        $isSocial = ($_POST['auth_type'] ?? 'local') === 'google';
        
        // Se for cadastro social, a senha não é obrigatória, mas precisamos
        // de um hash VAZIO ou de um valor NULO, dependendo do seu modelo User.
        // Usaremos um hash vazio (um valor seguro) para contas sociais, 
        // ou a senha se for local.
        $senha_post = $_POST['senha'] ?? null;
        $senha_final = $isSocial ? 'SOCIAL_ACCOUNT' : $senha_post; 

        // O Model User deve lidar com o hash, mas passamos a senha/marcador
        $userData = [
            'nome' => $_POST['nome'] ?? '',
            'email' => $_POST['email'] ?? '',
            // Use a senha normal para local, ou o marcador para social (o Model User lida com isso)
            'senha' => $senha_final, 
            'telefone' => $_POST['telefone'] ?? '',
            'cpf' => $_POST['cpf'] ?? '',
            'role_id' => 1,
            'auth_type' => $_POST['auth_type'] ?? 'local',
            'google_id' => $_POST['google_id'] ?? null, // Recebe do campo hidden
            'picture' => $_POST['picture'] ?? null // Recebe do campo hidden
        ];

        $enderecoData = [
            'cep' => $_POST['cep'] ?? '',
            'uf' => $_POST['uf'] ?? '',
            'cidade' => $_POST['cidade'] ?? '',
            'bairro' => $_POST['bairro'] ?? '',
            'logradouro' => $_POST['logradouro'] ?? '',
            'numero' => $_POST['numero'] ?? ''
        ];

        try {
            // 3. Salva Endereço
            $endereco_id = $this->enderecoDAO->create($enderecoData);
            $userData['endereco_id'] = $endereco_id;
        
            // 4. Salva Usuário
            $user = new User($userData);
            $user->save();
        
            // 5. Limpa a sessão social e faz login
            unset($_SESSION['social_register_data']);
            $_SESSION['sucess_message'] = 'Cadastro realizado com sucesso!';
            $_SESSION['logado'] = TRUE;
            $_SESSION['user_id'] = $user->getId();
            $_SESSION['user_picture'] = $user->getPicture() ?? null;
            $_SESSION['username'] = $user->getNome();
            header('Location: /');
            exit;
        } catch (\Exception $e) {
            error_log("Erro no cadastro de usuário: " . $e->getMessage());
            $_SESSION['error_message'] = 'Erro ao finalizar o cadastro. Tente novamente.';
            header('Location: /cadastro');
            exit;
        }
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
                // --- NOVO FLUXO: Usuário Google NOVO ou INCOMPLETO ---
                
                // 1. Armazena os dados do Google temporariamente na sessão
                $_SESSION['social_register_data'] = [
                    'nome' => $googleUser['name'] ?? '',
                    'email' => $googleUser['email'],
                    'google_id' => $googleUser['id'],
                    'auth_type' => 'google',
                    'picture' => $googleUser['picture'] ?? null
                ];
                
                // 2. Redireciona para o formulário de cadastro com um indicador
                $_SESSION['warning_message'] = '<p>⚠️ <b>Dados Incompletos!</b></p><p>Obrigado por se registrar com o Google. Por favor, complete os campos <b>Telefone, CPF</b> e <b>Endereço</b> para finalizar seu cadastro.</p>';
                header('Location: /cadastro?social=google');
                exit;
                
            } else {
                // --- Usuário Google EXISTENTE ---
                
                // 1. Atualiza dados (como google_id e foto)
                $this->userDAO->update($user['id'], [
                    'google_id' => $googleUser['id'], 
                    'auth_type' => 'google', 
                    'picture' => $googleUser['picture'] ?? $user['picture']
                ]);
                
                // 2. Faz login (pode ser necessário buscar o usuário atualizado)
                $user = $this->userDAO->getByEmail($googleUser['email']);
                $_SESSION['logado'] = TRUE;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['nome'];
                $_SESSION['user_picture'] = $user['picture'] ?? null;
                $_SESSION['sucess_message'] = 'Login realizado com sucesso!';
                header('Location: /');
                exit;
            }

        } catch (Exception $e) {
            error_log("Erro de Autenticação com Google: " . $e->getMessage());
            $_SESSION['error_message'] = 'Erro de Autenticação. Tente novamente.';
            header('Location: /login');
            exit;
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

                $_SESSION['sucess_message'] = "Senha redefinida com sucesso! Faça login novamente.";
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