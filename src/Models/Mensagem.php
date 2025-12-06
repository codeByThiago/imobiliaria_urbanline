<?php 

class Mensagem {
    private $id;
    private $remetente_id;
    private $destinatario_id;
    private $titulo;
    private $mensagem;
    private $link;
    private $lida;
    private $created_at;
    private $updated_at;
    
    // GETTERS E SETTERS

    public function __construct($data = []) {
        $this->id = $data['id'];
        $this->remetente_id = $data['remetente_id'];
        $this->destinatario_id = $data['destinatario_id'];
        $this->titulo = $data['titulo'];
        $this->mensagem = $data['mensagem'];
        $this->link = $data['link'];
        $this->lida = $data['lida'];
        $this->created_at = $data['created_at'];
        $this->updated_at = $data['updated_at'];
    }

    public function setId($id) { return $this->id = $id; }
    public function setRemetenteId($remetente_id) { return $this->remetente_id = $remetente_id; }
    public function setDestinatarioId($destinatario_id) { return $this->destinatario_id = $destinatario_id; }
    public function setTitulo($titulo) { return $this->titulo = $titulo; }
    public function setMensagem($mensagem) { return $this->mensagem = $mensagem; }
    public function setLink($link) { return $this->link = $link; }
    public function setLida($lida) { return $this->lida = $lida; }
    public function setCreatedAt($created_at) { return $this->created_at = $created_at; }
    public function setUpdatedAt($updated_at) { return $this->updated_at = $updated_at; }

    public function getId() { return $this->id; }
    public function getRemetenteId() { return $this->remetente_id; }
    public function getDestinatarioId() { return $this->destinatario_id; }
    public function getTitulo() { return $this->titulo; }
    public function getMensagem() { return $this->mensagem; }
    public function getLink() { return $this->link; }
    public function getLida() { return $this->lida; }
    public function getCreatedAt() { return $this->created_at; }
    public function getUpdatedAt() { return $this->updated_at; }
}

?>