<?php 

namespace DAOs;

use Config\Database;
use Exception;
use PDOException;
use PDO;

class BaseDAO extends Database {
    protected PDO $conn;
    protected string $table;

    public function __construct(string $table) {
        parent::__construct();
        $this->conn = $this->getConnection();
        $this->table = $table;
    }


    // public function create(array $data) {
    //     try {

    //     } catch (PDOException $e) {
    //         throw new Exception('Erro ao criar: ' . $e->getMessage());
    //     }
    // }

    // public function update(array $data) {
    //     try {
            
    //     } catch (PDOException $e) {
    //         throw new Exception('Erro ao atualizar: ' . $e->getMessage());
    //     }
    // }

    public function listAll() : ?array {
        try {
            $sql = "SELECT * FROM {$this->table}";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $data ? : null;

        } catch (PDOException $e) {
            throw new Exception('Erro ao listar: ' . $e->getMessage());
        }
    }

    public function selectById(int $id) : ?array {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE id = ?";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $result ? : null;
            
        } catch (PDOException $e) {
            throw new Exception("Erro ao selecionar por ID: " . $e->getMessage());
            return null;
        }
    }

    public function delete(int $id): bool {
        try {
            $sql = "DELETE FROM {$this->table} WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            throw new Exception('Erro ao deletar: ' . $e->getMessage());
        }
    }
}

?>