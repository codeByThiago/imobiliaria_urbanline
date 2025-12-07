<?php

include dirname(__FILE__, 2) . "/src/Core/Autoload.php";
include dirname(__FILE__, 2) . "/src/Core/Config.php";
require_once __DIR__ . '/../vendor/autoload.php';
include VIEWS . 'includes/funcoes.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Namespaces, Inclusões e Autoload
use Core\Autoload;
use Controllers\UserController;
use Controllers\AuthController;
use Controllers\ImoveisController;

Autoload::register();

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '', '/');


if (empty($uri)) {
    $uri = '/';
}

$userController = new UserController();
$authController = new AuthController();
$imoveisController = new ImoveisController();

try {
    switch ($uri) {
        
        case '/':
            $userController->index();
            break;
        case 'search':
            $imoveisController->search();
            break;
        case 'detalhe-imovel':
            $imoveisController->detalheImovel();
            break;
        case 'login':
            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                $authController->login();
            } else {
                $authController->showLoginForm();
            }
            break;
        case 'cadastro':
            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                $authController->cadastro();
            } else {
                $authController->showCadastroForm();
            }
            break;
        case 'logout':
            $authController->logout();
            break;
        case 'user/google-login':
            $authController->googleLogin();
            break;
        case 'user/google-callback':
            $authController->googleCallback();
            break;
        default:
            $userController->errorPage404();
            exit();
    }
} catch (Exception $e) {
    // Tratamento de exceções que possam vir dos Controllers/DAOs
    http_response_code(500);
    echo "<h1>Erro Interno do Servidor (500)</h1><p>Ocorreu um erro: " . $e->getMessage() . "</p>";
}