<?php 

namespace DAOs;

use Models\Endereco;
use PDO;
use PDOException;
use Exception;

class EnderecoDAO extends BaseDAO {

    public function __construct() {
        parent::__construct('endereco');
    }

    public function create(Endereco $endereco): int {
        try {
            $sql = "INSERT INTO endereco (cep, uf, cidade, bairro, logradouro, numero) VALUES (:cep, :uf, :cidade, :bairro, :logradouro, :numero)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':cep' => $endereco->getCep(),
                ':uf' => $endereco->getUf(),
                ':cidade' => $endereco->getCidade(),
                ':bairro' => $endereco->getBairro(),
                ':logradouro' => $endereco->getLogradouro(),
                ':numero' => $endereco->getNumero()
            ]);
            return (int)$this->conn->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Erro ao criar endereço: " . $e->getMessage());
        }
    }
}

?>