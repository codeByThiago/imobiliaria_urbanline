<?php 

namespace DAOs;

use Core\Database;
use Exception;
use PDO;
use PDOException;

abstract class BaseDAO extends Database {
    public string $table;
    public PDO $conexao;

    public function __construct(string $table) {
        parent::__construct();
        $this->conexao = parent::getConnection();
        $this->table = $table;
    }

    public function create(array $data) : int {
        try {
            if(isset($data['id'])) {
                unset($data['id']);
            }

            $columns = implode(', ', array_keys($data));
            $placeholders = implode(', ', array_map(fn($dado) => ":$dado", array_keys($data)));

            $sql = "INSERT INTO {$this->table} ($columns) VALUES($placeholders)";

            echo $sql . "<br>";


            $stmt = $this->conexao->prepare($sql);

            foreach ($data as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }

            $stmt->execute();
            return $newId = $this->conexao->lastInsertId();

        } catch (Exception $e) {
            throw new Exception("Erro ao inserir dado na tabela {$this->table}: " . $e->getMessage());
        }
    }

    public function listAll() : ?array {
        try {
            $sql = "SELECT * FROM {$this->table}";
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute();
            
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $data ? : null;
        } catch (PDOException $e) {
            throw new Exception("Erro ao selecionar dados de {$this->table}: " . $e->getMessage());
            return null;
        }
    }

    public function update(int $id, array $data) : bool {
        try {
            if(empty($data)) {
                throw new Exception("Nenhum dado foi enviado para atualizar");
            }

            if(isset($data['id'])) {
                unset($data['id']);
            }
            
            $setValues = implode(', ', array_map(fn($key) => "$key = :$key", array_keys($data)));

            $sql = "UPDATE {$this->table} SET {$setValues} WHERE id = :id";
            $stmt = $this->conexao->prepare($sql);

            $stmt->bindValue(':id', $id);
            
            echo $sql . '<br>';
            foreach ($data as $key => $value) {
                $stmt->bindValue(":$key", $value);
                echo ":$key", $value . '<br>';
            }

            return $stmt->execute();

        } catch (PDOException $e) {
            throw new Exception("Erro ao atualizar: " . $e->getMessage());
        }
    }

    public function selectById(int $id) : ?array {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(":id", $id);
            $stmt->execute();

            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            return $data ? : null;
        } catch (PDOException $e) {
            throw new Exception("Erro ao selecionar por ID: " . $e->getMessage());
        }
    }

    public function delete(int $id) : bool {
        try {
            $sql = "DELETE FROM {$this->table} WHERE id = ?";
            $stmt = $this->conexao->prepare($sql);
            return $stmt->execute([$id]);

        } catch (PDOException $e) {
            throw new Exception("Erro ao deletar dado da tabela {$this->table}: " . $e->getMessage());
            return false;
        }
    }
}

?>