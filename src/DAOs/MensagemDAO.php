<?php 

namespace DAOs;

use DAOs\BaseDAO;

class MensagemDAO extends BaseDAO {
    public function __construct() {
        parent::__construct('mensagem');
    }

    public function findByUserID(int $userId) : ?array {
        try {
            $sql = "SELECT * FROM mensagem WHERE user_id = :user_id";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(':user_id', $userId, \PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $data ?: null;
        } catch (\PDOException $e) {
            throw new \Exception("MensagemDAO::findByUserID - Erro ao buscar mensagens do usuário: " . $e->getMessage(), (int)$e->getCode(), $e);
        }
    }

    public function findByUserIDAndReadStatus(int $userId, bool $lida) : ?array {
        try {
            $sql = "SELECT * FROM mensagem WHERE user_id = :user_id AND lida = :lida";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(':user_id', $userId, \PDO::PARAM_INT);
            $stmt->bindValue(':lida', $lida, \PDO::PARAM_BOOL);
            $stmt->execute();
            $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $data ?: null;
        } catch (\PDOException $e) {
            throw new \Exception("MensagemDAO::findByUserIDAndReadStatus - Erro ao buscar mensagens do usuário por status de leitura: " . $e->getMessage(), (int)$e->getCode(), $e);
        }
    }
}
?>