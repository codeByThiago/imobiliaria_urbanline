<?php

namespace Models;

use DAOs\UserDAO;

class User {
    private ?int $id;
    private string $nome;
    private string $email;
    private string $senha;
    private string $telefone;
    private string $cpf;
    private ?int $role_id;
    private ?int $endereco_id;

    public function __construct(array $data = []) {
        $this->id = $data['id'] ?? null;
        $this->nome = $data['nome'] ?? '';
        $this->email = $data['email'] ?? '';
        
        // Aplica o setter para garantir o hashing da senha no construct
        if (isset($data['senha'])) {
             $this->setSenha($data['senha']);
        } else {
            $this->senha = '';
        }
        
        $this->telefone = $data['telefone'] ?? '';
        $this->cpf = $data['cpf'] ?? '';
        $this->role_id = $data['role_id'] ?? 1;
        $this->endereco_id = $data['endereco_id'] ?? null;
    }

    // Setters e getters para o usuário
    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): void { $this->id = $id; }

    public function getNome(): string { return $this->nome; }
    public function setNome(string $nome): void { $this->nome = $nome; }

    public function getEmail(): string { return $this->email; }
    public function setEmail(string $email): void { $this->email = $email; }

    public function getSenha(): string { return $this->senha; }
    public function setSenha(string $senha): void {
        if (!password_get_info($senha)['algo']) {
            $this->senha = password_hash($senha, PASSWORD_DEFAULT);
        } else {
            $this->senha = $senha;
        }
    }

    public function getTelefone(): string { return $this->telefone; }
    public function setTelefone(string $telefone): void { $this->telefone = $telefone; }

    public function getCpf(): string { return $this->cpf; }
    public function setCpf(string $cpf): void { $this->cpf = $cpf; }

    public function getRoleId(): ?int { return $this->role_id; }
    public function setRoleId(?int $role_id): void { $this->role_id = $role_id; }

    // Get e set do endereço (objeto)
    public function getEnderecoId(): ?int { return $this->endereco_id; }
    public function setEnderecoId(int $endereco_id): void { $this->endereco_id = $endereco_id; }

    // Método para verificar senha
    public function verificarSenha(string $senhaDigitada): bool {
        return password_verify($senhaDigitada, $this->senha);
    }

    public function toArray() : array {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'email' => $this->email,
            'senha' => $this->senha,
            'telefone' => $this->telefone,
            'cpf' => $this->cpf,
            'role_id' => $this->role_id,
            'endereco_id' => $this->endereco_id
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
