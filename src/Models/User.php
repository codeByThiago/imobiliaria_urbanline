<?php

namespace Models;

use DAOs\UserDAO;

class User {
    private ?int $id;
    private string $nome;
    private string $email;
    private string $password;
    private string $telefone;
    private string $cpf;
    private string $picture;
    private ?int $role_id;
    private ?int $endereco_id;
    private string $google_id;
    private string $auth_type;

    public function __construct(array $data = []) {
        $this->id = $data['id'] ?? null;
        $this->nome = $data['nome'] ?? '';
        $this->email = $data['email'] ?? '';
        
        // Aplica o setter para garantir o hashing da senha no construct
        if (isset($data['password'])) {
             $this->setSenha($data['password']);
        } else {
            $this->password = '';
        }
        
        $this->telefone = $data['telefone'] ?? '';
        $this->cpf = $data['cpf'] ?? '';
        $this->picture = $data['picture'] ?? '';
        $this->role_id = $data['role_id'] ?? 1;
        $this->endereco_id = $data['endereco_id'] ?? null;
        $this->google_id = $data['google_id'] ?? '';
        $this->auth_type = $data['auth_type'] ?? 'local';
    }

    // Setters e getters para o usuário
    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): void { $this->id = $id; }

    public function getNome(): string { return $this->nome; }
    public function setNome(string $nome): void { $this->nome = $nome; }

    public function getEmail(): string { return $this->email; }
    public function setEmail(string $email): void { $this->email = $email; }

    public function getSenha(): string { return $this->password; }
    public function setSenha(string $password): void {
        if (!password_get_info($password)['algo']) {
            $this->password = password_hash($password, PASSWORD_DEFAULT);
        } else {
            $this->password = $password;
        }
    }

    public function getTelefone(): string { return $this->telefone; }
    public function setTelefone(string $telefone): void { $this->telefone = $telefone; }

    public function getCpf(): string { return $this->cpf; }
    public function setCpf(string $cpf): void { $this->cpf = $cpf; }

    public function getPicture(): string { return $this->picture; }
    public function setPicture(string $picture): void { $this->picture = $picture; }

    public function getRoleId(): ?int { return $this->role_id; }
    public function setRoleId(?int $role_id): void { $this->role_id = $role_id; }

    // Get e set do endereço (objeto)
    public function getEnderecoId(): ?int { return $this->endereco_id; }
    public function setEnderecoId(int $endereco_id): void { $this->endereco_id = $endereco_id; }

    // Método para verificar senha
    public function verificarSenha(string $senhaDigitada): bool {
        return password_verify($senhaDigitada, $this->password);
    }

    public function toArray() : array {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'email' => $this->email,
            'password' => $this->password,
            'telefone' => $this->telefone,
            'cpf' => $this->cpf,
            'picture' => $this->picture,
            'role_id' => $this->role_id,
            'endereco_id' => $this->endereco_id,
            'google_id' => $this->google_id,
            'auth_type' => $this->auth_type,
        ];
    }

    public function save() : int|bool {
        // 1. Instancia o DAO
        $userDAO = new UserDAO();
        
        // 2. Obtém os dados do objeto em formato de array
        $data = $this->toArray();
        $id = $this->getId();
        
        // 3. Verifica se é CREATE ou UPDATE
        if ($id === null) {
            // --- CREATE (Inserir) ---
            $newId = $userDAO->create($data);
            
            // É crucial atualizar o ID do objeto após a inserção
            $this->setId($newId);
            
            return $newId;
            
        } else {
            unset($data['id']); 
            
            return $userDAO->update($id, $data);
        }
    }
}
?>

<!-- ----------------- FEITO -----------------  -->
