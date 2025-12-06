<?php
namespace DAOs;

use Models\Usuario;
use PDOException;
use Exception;

class UsuarioDAO extends BaseDAO {
    public function __construct() {
        parent::__construct('usuarios');
    }

    public function create(Usuario $usuario): int {
        try {
            $sql = "INSERT INTO usuarios (nome, email, senha, telefone, cpf, endereco_id, role_id) 
                    VALUES (:nome, :email, :senha, :telefone, :cpf, :endereco_id, :role_id)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':nome' => $usuario->getNome(),
                ':email' => $usuario->getEmail(),
                ':senha' => $usuario->getSenha(),
                ':telefone' => $usuario->getTelefone(),
                ':cpf' => $usuario->getCpf(),
                ':endereco_id' => $usuario->getEnderecoId(),
                ':role_id' => $usuario->getRoleId()
            ]);
            return (int)$this->conn->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Erro ao criar usuário: " . $e->getMessage());
        }
    }

    public function selectUsuarioById(int $id) : ?Usuario {
        try {
            $sql = "SELECT u.*, e.id as endereco_id, e.cep, e.uf, e.cidade, e.bairro, e.logradouro, e.numero 
                    FROM {$this->table} u
                    LEFT JOIN endereco e ON u.endereco = e.id
                    WHERE u.id = :id";
            $stmt = $this->conn->prepare($sql);

            $stmt->execute([':id' => $id]);
            $data = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $data ? new Usuario($data) : null;
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar usuário por id: " . $e->getMessage());
        }
    }

    public function selectByEmail(string $email) : ?Usuario {
        try {
            $sql = "SELECT u.*, e.id as endereco_id, e.cep, e.uf, e.cidade, e.bairro, e.logradouro, e.numero 
                    FROM {$this->table} u
                    LEFT JOIN endereco e ON u.endereco = e.id
                    WHERE u.email = :email";
            $stmt = $this->conn->prepare($sql);

            $stmt->execute([':email' => $email]);
            $data = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $data ? new Usuario($data) : null;
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar usuário por email: " . $e->getMessage());
        }
    }

    public function update(Usuario $usuario) : bool {
        try {
            $sql = "UPDATE usuarios SET 
                        nome = :nome, 
                        email = :email, 
                        senha = :senha, 
                        telefone = :telefone, 
                        cpf = :cpf, 
                        endereco_id = :endereco_id, 
                        role_id = :role_id
                    WHERE id = :id";
                    
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':nome' => $usuario->getNome(),
                ':email' => $usuario->getEmail(),
                ':senha' => $usuario->getSenha(),
                ':telefone' => $usuario->getTelefone(),
                ':cpf' => $usuario->getCpf(),
                ':endereco_id' => $usuario->getEnderecoId(),
                ':role_id' => $usuario->getRoleId(),
                ':id' => $usuario->getId()
            ]);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            die ("Erro ao atualizar usuário: " . $e->getMessage());
        }
    }
}
