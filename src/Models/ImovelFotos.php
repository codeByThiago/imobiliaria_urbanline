<?php 

namespace Models;

use DAOs\ImovelFotosDAO;

class ImovelFotos {
    private ?int $id;
    private ?int $imovel_id;
    private string $url;
    private bool $destaque;

    // GETTERS E SETTERS

    public function __construct($data = []) {
        $this->id = $data['id'] ?? null;
        $this->imovel_id = $data['imovel_id'] ?? null;
        $this->url = $data['url'] ?? '';
        $this->destaque = $data['destaque'] ?? false;
    }

    public function setId($id) { return $this->id = $id; }
    public function setImovelId($imovel_id) { return $this->imovel_id = $imovel_id; }
    public function setUrl($url) { return $this->url = $url; }
    public function setDestaque($destaque) { return $this->destaque = $destaque; }

    public function getId() { return $this->id; }
    public function getImovelId() { return $this->imovel_id; }
    public function getUrl() { return $this->url; }
    public function getDestaque() { return $this->destaque; }

    public function toArray() : array {
        return [
            'id' => $this->id,
            'imovel_id' => $this->imovel_id,
            'url' => $this->url,
            'destaque' => $this->destaque 
        ];
    }
    
    public function save() : int|bool {
        $imovelFotosDAO = new ImovelFotosDAO();
        
        $data = $this->toArray();
        $id = $this->getId();
        
        if ($id === null) {
            // --- CREATE (Inserir) ---
            $newId = $imovelFotosDAO->create($data);
            $this->setId($newId);
            return $newId;
            
        } else {
            // --- UPDATE (Atualizar) ---
            unset($data['id']); 
            return $imovelFotosDAO->update($id, $data);
        }
    }
}

?>