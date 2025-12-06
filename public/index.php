<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// require __DIR__ . '/../vendor/autoload.php';
// $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
// $dotenv->load();

require_once "../src/Config/Config.php";
require_once "../src/Config/Autoload.php";

// Namespaces, Inclusões e Autoload
use Config\Autoload;
use Controllers\UsuarioController;
use Controllers\AuthController;

Autoload::register();


$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '', '/');


if (empty($uri)) {
    $uri = '/';
}

$usuarioController = new UsuarioController();
$authController = new AuthController();

try {
    switch ($uri) {
        
        case '/':
            $usuarioController->index();
            break;
            
        case 'login':
            // O login precisa diferenciar GET (exibir form) de POST (processar login)
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $authController->showLoginForm();
            } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $authController->login();
            } else {
                http_response_code(405);
                echo "Método Não Permitido.";
            }
            break;
        
        case 'logout':
            $authController->logout();
            break;
            
        case 'cadastro':
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $usuarioController->showRegisterForm();
            } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $usuarioController->register();
            }
            break;

        // case 'esqueci-senha':
            // break;
        // case 'dashboard':
            // break;
        // case user/adicionar-imovel:
            // break;
        // case user/dashboard;
            // break;
        // case 'user/config':
            // break;
        default:
            $usuarioController->errorPage404();
            exit();
    }
} catch (Exception $e) {
    // Tratamento de exceções que possam vir dos Controllers/DAOs
    http_response_code(500);
    echo "<h1>Erro Interno do Servidor (500)</h1><p>Ocorreu um erro: " . $e->getMessage() . "</p>";
}