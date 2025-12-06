<?php 

require_once __DIR__ . "/../models/ImovelFotos.php";

class ImovelFotosDAO {
    private $conn;

    public function __construct(PDO $conn) {
        $this->conn = $conn;
    }

    public function create(ImovelFotos $imovel_fotos) : int {
        return $this->conn->lastInsertId();
    }

    public function listAll() : array {
        try {
            $sql = "SELECT * FROM imovel_fotos";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $imovel_fotos = [];
            foreach ($data as $foto_imovel) {
                $imovel_fotos[] = new ImovelFotos($foto_imovel);
            }
            return $imovel_fotos;
        } catch (PDOException $e) {
            throw new Exception("ImovelFotosDAO::listAll - Erro ao listar fotos do imóvel: " . $e->getMessage(), (int)$e->getCode(), $e);
        }
    }

    public function findById(int $id) : ?ImovelFotos {
        try {
            $sql = "SELECT * FROM imovel_fotos WHERE id = ?";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            return $data ? new ImovelFotos($data) : null;
        } catch (PDOException $e) {
            throw new Exception("ImovelFotosDAO::findById - Erro ao buscar foto de imóvel: " . $e->getMessage(), (int)$e->getCode(), $e);
        }
    }

    public function findByImovelId() : array {
        try {
            $sql = "SELECT * FROM imovel_fotos f JOIN imoveis i ON f.imovel_id = i.id";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $imovel_fotos = [];
            foreach ($imovel_fotos as $imovel_foto) {
                $imovel_fotos[] = new ImovelFotos($imovel_foto);
            }
            return $imovel_fotos;
        } catch (PDOException $e) {
            throw new Exception("ImovelFotosDAO::findByImovelId - Erro ao buscar foto de imóvel: " . $e->getMessage(), (int)$e->getCode(), $e);
        }
    }

    public function update(ImovelFotos $imovel_fotos) : bool {
        try {
            $sql = "UPDATE imovel_fotos SET imovel_id=:imovel_id, destaque=:destaque, url=:url";

            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':imovel_id' => $imovel_fotos->getImovelId(),
                ':destaque' => $imovel_fotos->getDestaque(),
                ':url' => $imovel_fotos->getUrl()
            ]);
        } catch (PDOException $e) {
            throw new Exception("ImovelFotsDAO::update - Erro ao atualizar foto do imóvel: " . $e->getMessage(), (int)$e->getCode(), $e);
        }
    }

    public function delete(int $id) : bool {
        try {
            $sql = "DELETE FROM imovel_fotos WHERE id = ?";

            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            throw new Exception("ImovelFotosDAO::delete - Erro ao deletar foto de imóvel: " . $e->getMessage(), (int)$e->getCode(), $e);
        }
    }
}

?>