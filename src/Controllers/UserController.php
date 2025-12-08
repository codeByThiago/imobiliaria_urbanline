<?php

namespace Controllers;

use Models\User;
use Models\Endereco;
use DAOs\EnderecoDAO;
use DAOs\UserDAO;
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
    private EnderecoDAO $enderecoDAO;
    private UserDAO $userDAO;

    public function __construct() {
        $this->userDAO = new UserDAO();
        $this->enderecoDAO = new EnderecoDAO();
    }

    
    public function index() {
        renderView('user/home');
    }

    public function dashboard() {
        renderView('user/dashboard');
    }

    public function errorPage404() {
        renderView('user/404');
    }
}