<?php 

namespace DAOs;

use DAOs\BaseDAO;
use PDO;

class EmailChangesDAO extends BaseDAO {
    public function __construct() {
        parent::__construct('email_changes');
    }

    public function buscarPorToken(string $tokenHash): ?array {
        $sql = "SELECT * FROM email_changes WHERE token_hash = :token_hash AND expires_at > NOW() LIMIT 1";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([':token_hash' => $tokenHash]);
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);
        return $dados ?: null;
    }
}

?>