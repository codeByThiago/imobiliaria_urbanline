<?php 

namespace Models;

use DAOs\MensagemDAO;

class Mensagem {
    private ?int $id;
    private ?int $remetente_id;
    private ?int $destinatario_id;
    private string $titulo;
    private string $mensagem;
    private string $link;
    private bool $lida;
    private string $created_at;
    private string $updated_at;
    
    // GETTERS E SETTERS

    public function __construct($data = []) {
        $this->id = $data['id'] ?? null;
        $this->remetente_id = $data['remetente_id'] ?? null;
        $this->destinatario_id = $data['destinatario_id'] ?? null;
        $this->titulo = $data['titulo'] ?? '';
        $this->mensagem = $data['mensagem'] ?? '';
        $this->link = $data['link'] ?? '';
        $this->lida = $data['lida'] ?? false;
        $this->created_at = $data['created_at'] ?? '';
        $this->updated_at = $data['updated_at'] ?? '';
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

    public function toArray() : array {
        return [
            'id' => $this->id,
            'remetente_id' => $this->remetente_id,
            'destinatario_id' => $this->destinatario_id,
            'titulo' => $this->titulo,
            'mensagem' => $this->mensagem,
            'link' => $this->link,
            'lida' => $this->lida,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }

    public function save() : int|bool {
        $mensagemDAO = new MensagemDAO();

        $data = $this->toArray();
        $id = $this->getId();

        if ($id === null) {
            // --- CREATE (Inserir) ---
            $newId = $mensagemDAO->create($data);
            $this->setId($newId);
            return $newId;
            
        } else {
            // --- UPDATE (Atualizar) ---
            unset($data['id']); 
            
            return $mensagemDAO->update($id, $data);
        }
    }
}

?>