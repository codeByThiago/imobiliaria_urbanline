<?php 

require_once __DIR__ . "/../models/Imovel.php";

class ImovelDAO {
    private $conn;

    public function __construct(PDO $conn) {
        $this->conn = $conn;
    }

    public function create(Imovel $imovel) : int {
        try {
            $sql = "INSERT INTO imoveis (nome, usuario_id, endereco_id, tipo_imovel, valor, area, descricao, quant_quartos, quant_suites, quant_cozinhas, quant_banheiros, quant_piscinas, vagas_garagem, status, mobiliado)
            VALUES (:nome, :usuario_id, :endereco_id, :tipo_imovel, :valor, :area, :descricao, :quant_quartos, :quant_suites, :quant_cozinhas, :quant_banheiros, :quant_piscinas, :vagas_garagem, :status, :mobiliado)";
    
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':nome' => $imovel->getNome(),
                ':usuario_id' => $imovel->getUsuarioId(),
                ':endereco_id' => $imovel->getEnderecoId(),
                ':tipo_imovel' => $imovel->getTipoImovel(),
                ':valor' => $imovel->getValor(),
                ':area' => $imovel->getArea(),
                ':descricao' => $imovel->getDescricao(),
                ':quant_quartos' => $imovel->getQuantQuartos(),
                ':quant_suites' => $imovel->getQuantSuites(),
                ':quant_cozinhas' => $imovel->getQuantCozinhas(),
                ':quant_banheiros' => $imovel->getQuantBanheiros(),
                ':quant_piscinas' => $imovel->getQuantPiscinas(),
                ':vagas_garagem' => $imovel->getVagasGaragem(),
                ':status' => $imovel->getStatus(),
                ':mobiliado' => $imovel->getMobiliado()
            ]);
            return (int)$this->conn->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("ImovelDAO::create - Erro ao criar imóvel: " . $e->getMessage(), (int)$e->getCode(), $e);
        }
    }

    public function listAll() : array {
        try {
            $sql = "SELECT * FROM imoveis";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $imoveis = [];
            foreach ($data as $imovel) {
                $imoveis[] = new Imovel($imovel);
            }
            return $imoveis;
        } catch (PDOException $e) {
            throw new Exception("ImovelDAO::listAll - Erro ao listar imóvel: " . $e->getMessage(), (int)$e->getCode(), $e);
        }
    }

    public function findById(int $id) : ?Imovel {
        try {
            $sql = "SELECT * FROM imoveis WHERE id = ?";
    
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            return $data ? new Imovel($data) : null;
        } catch (PDOException $e) {
            throw new Exception("ImovelDAO::findById - Erro ao buscar por imóvel: " . $e->getMessage(), (int)$e->getCode(), $e);
        }
    }

    public function update(Imovel $imovel) : bool {
        try {
            $sql = "UPDATE imoveis SET nome=:nome, usuario_id=:usuario_id, endereco_id=:endereco_id, tipo_imovel=:tipo_imovel, valor=:valor, area=:area, descricao=:descricao, quant_quartos=:quant_quartos, quant_suites=:quant_suites, quant_cozinhas=:quant_cozinhas, quant_banheiros=:quant_banheiros, quant_piscinas=:quant_piscinas, vagas_garagem=:vagas_garagem, status=:status, mobiliado=:mobiliado WHERE id = :id";
            
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':id' => $imovel->getId(),
                ':nome' => $imovel->getNome(),
                ':usuario_id' => $imovel->getUsuarioId(),
                ':endereco_id' => $imovel->getEnderecoId(),
                ':tipo_imovel' => $imovel->getTipoImovel(),
                ':valor' => $imovel->getValor(),
                ':area' => $imovel->getArea(),
                ':descricao' => $imovel->getDescricao(),
                ':quant_quartos' => $imovel->getQuantQuartos(),
                ':quant_suites' => $imovel->getQuantSuites(),
                ':quant_cozinhas' => $imovel->getQuantCozinhas(),
                ':quant_banheiros' => $imovel->getQuantBanheiros(),
                ':quant_piscinas' => $imovel->getQuantPiscinas(),
                ':vagas_garagem' => $imovel->getVagasGaragem(),
                ':status' => $imovel->getStatus(),
                ':mobiliado' => $imovel->getMobiliado(),
            ]);
        } catch (PDOException $e) {
            throw new Exception("ImovelDAO::update - Erro ao atualizar imóvel: " . $e->getMessage(), (int)$e->getCode(), $e);
        }
    }

    public function delete(int $id) : bool {
        try {
            $sql = "DELETE FROM imoveis WHERE id = ?";
    
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            throw new Exception("ImovelDAO::delete - Erro ao deletar imóvel: " . $e->getMessage(), (int)$e->getCode(), $e);
        }
    }
}

?>