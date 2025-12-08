<?php 

namespace DAOs;

use DAOs\BaseDAO;
use PDO;
use Exception;
use PDOException;

class MensagemDAO extends BaseDAO {

    public function __construct() {
        parent::__construct('mensagens'); // 'mensagens' é o nome da tabela
    }

    public function listPaginatedByDestinatario($destinatarioId, $page = 1, $limit = 5) {
        $offset = ($page - 1) * $limit;

        $sql = "SELECT m.*, r.nome as remetente_nome, r.picture as remetente_picture
                FROM mensagens m
                LEFT JOIN users r ON m.remetente_id = r.id
                WHERE m.destinatario_id = :destinatario_id
                ORDER BY m.created_at DESC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(':destinatario_id', $destinatarioId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countTotalByDestinatario($destinatarioId) {
        $sql = "SELECT COUNT(*) FROM mensagens WHERE destinatario_id = :destinatario_id";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(':destinatario_id', $destinatarioId, PDO::PARAM_INT);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public function markAsRead($mensagemId, $destinatarioId) {
        $sql = "UPDATE mensagens SET lida = TRUE WHERE id = :id AND destinatario_id = :destinatario_id";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(':id', $mensagemId, PDO::PARAM_INT);
        $stmt->bindValue(':destinatario_id', $destinatarioId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    
    public function findByIdAndDestinatario($mensagemId, $destinatarioId) {
        $sql = "SELECT m.*, r.nome as remetente_nome, r.picture as remetente_picture
                FROM mensagens m
                LEFT JOIN users r ON m.remetente_id = r.id
                WHERE m.id = :id AND m.destinatario_id = :destinatario_id";

        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(':id', $mensagemId, PDO::PARAM_INT);
        $stmt->bindValue(':destinatario_id', $destinatarioId, PDO::PARAM_INT);
        $stmt->execute();
        $mensagem = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($mensagem && !$mensagem['lida']) {
            $this->markAsRead($mensagemId, $destinatarioId);
            $mensagem['lida'] = 1; // Atualiza o status no array retornado
        }

        return $mensagem;
    }
    
    public function countUnreadByDestinatario($destinatarioId) {
        $sql = "SELECT COUNT(*) FROM mensagens WHERE destinatario_id = :destinatario_id AND lida = FALSE";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(':destinatario_id', $destinatarioId, PDO::PARAM_INT);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

}
?>