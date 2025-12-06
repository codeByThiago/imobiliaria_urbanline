<?php

namespace DAOs;

use DAOs\BaseDAO;
use PDO;

class PasswordResetsDAO extends BaseDAO {
    public function __construct() {
        parent::__construct('password_resets');
    }

    public function buscarPorToken(string $tokenHash): ?array {
        $sql = "SELECT * FROM password_resets WHERE token_hash = :token_hash AND expires_at > NOW() LIMIT 1";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([':token_hash' => $tokenHash]);
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);
        return $dados ?: null;
    }
}
