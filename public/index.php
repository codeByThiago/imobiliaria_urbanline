<?php

include dirname(__FILE__, 2) . "/src/Core/Autoload.php";
include dirname(__FILE__, 2) . "/src/Core/Config.php";
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Namespaces, Inclusões e Autoload
use Core\Autoload;
use Controllers\UserController;
use Controllers\AuthController;

Autoload::register();


$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '', '/');


if (empty($uri)) {
    $uri = '/';
}

$userController = new UserController();
$authController = new AuthController();

try {
    switch ($uri) {
        
        case '/':
            $userController->index();
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
                $userController->showRegisterForm();
            } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $userController->register();
            }
            break;

        // case 'esqueci-senha':
            // break;
        case 'dashboard':
            $userController->dashboard();
            break;
        // case user/adicionar-imovel:
            // break;
        // case user/dashboard;
            // break;
        // case 'user/config':
            // break;
        default:
            $userController->errorPage404();
            exit();
    }
} catch (Exception $e) {
    // Tratamento de exceções que possam vir dos Controllers/DAOs
    http_response_code(500);
    echo "<h1>Erro Interno do Servidor (500)</h1><p>Ocorreu um erro: " . $e->getMessage() . "</p>";
}