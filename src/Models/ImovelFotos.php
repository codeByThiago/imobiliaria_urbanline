<?php 

class ImovelFotos {
    private $id;
    private $imovel_id;
    private $url;
    private $destaque;

    // GETTERS E SETTERS

    public function __construct($data = []) {
        $this->id = $data['id'];
        $this->imovel_id = $data['imovel_id'];
        $this->url = $data['url'];
        $this->destaque = $data['destaque'];
    }

    public function setId($id) { return $this->id = $id; }
    public function setImovelId($imovel_id) { return $this->imovel_id = $imovel_id; }
    public function setUrl($url) { return $this->url = $url; }
    public function setDestaque($destaque) { return $this->destaque = $destaque; }

    public function getId() { return $this->id; }
    public function getImovelId() { return $this->imovel_id; }
    public function getUrl() { return $this->url; }
    public function getDestaque() { return $this->destaque; }

}

?>