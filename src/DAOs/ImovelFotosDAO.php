<?php 

namespace DAOs;

use Models\ImovelFotos;
use DAOs\BaseDAO;
use Exception;
use PDO;
use PDOException;

class ImovelFotosDAO extends BaseDAO {
    public function __construct() {
        parent::__construct("imovel_fotos");
    }

    public function findByImovelId(int $imovelId) : array {
        try {
            $sql = "SELECT f.* FROM imovel_fotos f
                    JOIN imoveis i ON f.imovel_id = i.id
                    WHERE f.imovel_id = :imovel_id";

            $stmt = $this->conexao->prepare($sql);
            
            $stmt->bindValue(':imovel_id', $imovelId, PDO::PARAM_INT);
            
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $imovel_fotos_models = $data;
            
            return $imovel_fotos_models;
            
        } catch (PDOException $e) {
            throw new Exception("ImovelFotosDAO::findByImovelId - Erro ao buscar foto de imóvel: " . $e->getMessage(), (int)$e->getCode(), $e);
        }
    }
}
?>