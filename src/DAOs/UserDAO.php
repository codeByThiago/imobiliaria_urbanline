<?php 

namespace DAOs;

use DAOs\BaseDAO;
use PDO;
use PDOException;
use Exception;

class UserDAO extends BaseDAO {
    public function __construct() {
        parent::__construct('users');
    }

    public function getByEmail($email) {
        try {
            $sql = "SELECT * FROM users WHERE email = :email";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(':email', $email);
            $stmt->execute();

            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            return $usuario ?? 'Usuário não encontrado';
        } catch (PDOException $e) {
            throw new Exception("Erro ao procurar usuário: " . $e->getMessage());
        }
    }

    public function getPictureByID($id) {
        try {
            $stmt = $this->conexao->prepare("SELECT picture FROM users WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            throw new Exception("Erro ao atualizar foto de usuário: " . $e->getMessage());
        }
        
    }

    public function atualizarFoto($id, $foto) : bool {
        try {
            $sql = "UPDATE users SET picture = ? WHERE id = ?";
            $stmt = $this->conexao->prepare($sql);
            return $stmt->execute([$foto, $id]);
        } catch (PDOException $e) {
            throw new Exception("Erro ao atualizar foto de usuário: " . $e->getMessage());
        }
    }

    public function atualizarNome(int $id, string $nome): bool {
        try {
            $stmt = $this->conexao->prepare("UPDATE users SET name = ? WHERE id = ?");
            return $stmt->execute([$nome, $id]);
        } catch (PDOException $e) {
            throw new Exception("Erro ao atualizar nome: " . $e->getMessage());
        }
    }

    public function atualizarEmail(int $id, string $email): bool {
        try {
            $stmt = $this->conexao->prepare("UPDATE users SET email = ? WHERE id = ?");
            return $stmt->execute([$email, $id]);
        } catch (PDOException $e) {
            throw new Exception("Erro ao atualizar e-mail: " . $e->getMessage());
        }
    }

    public function atualizarSenha(int $id, string $senha): bool {
        try {
            // Criptografa a nova senha
            $hashedPassword = password_hash($senha, PASSWORD_DEFAULT);

            $stmt = $this->conexao->prepare("UPDATE users SET password = ? WHERE id = ?");
            return $stmt->execute([$hashedPassword, $id]);
        } catch (PDOException $e) {
            throw new Exception("Erro ao atualizar senha: " . $e->getMessage());
        }
    }
}

?>