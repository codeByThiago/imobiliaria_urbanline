<?php

namespace Models;

use DAOs\PasswordResetsDAO; // Assumindo que você tem este DAO

class PasswordResets {
    private ?int $id;
    private int $usuario_id;
    private string $token_hash; // Renomeado para clareza
    private string $expire_at;
    private bool $usado;
    
    // Propriedade temporária para armazenar o token puro a ser enviado
    private string $plain_token;

    public function __construct(int $usuario_id, bool $usado = false) {
        $this->id = null;
        $this->usuario_id = $usuario_id;
        
        // --- Geração de Token ---
        // 1. Gera o token puro (para envio por e-mail)
        $this->plain_token = bin2hex(random_bytes(32));
        
        // 2. Armazena apenas o HASH no objeto (e no banco de dados)
        $this->token_hash = hash('sha256', $this->plain_token);

        $this->expire_at = date('Y-m-d H:i:s', time() + 3600);
        $this->usado = $usado;
    }

    // --- GETTERS e SETTERS (Ajustados) ---

    public function setId(?int $id): void { $this->id = $id; }
    public function setUsuarioId(int $usuario_id): void { $this->usuario_id = $usuario_id; }
    public function setTokenHash(string $token_hash): void { $this->token_hash = $token_hash; }
    public function setExpireAt(string $expire_at): void { $this->expire_at = $expire_at; }
    public function setUsado(bool $usado): void { $this->usado = $usado; }
    
    public function getId(): ?int { return $this->id; }
    public function getUsuarioId(): int { return $this->usuario_id; }
    public function getTokenHash(): string { return $this->token_hash; }
    public function getExpireAt(): string { return $this->expire_at; }
    public function getUsado(): bool { return $this->usado; }

    public function getPlainToken(): string { return $this->plain_token; }

    // --- MÉTODOS DE PERSISTÊNCIA ---

    public function toArray() : array {
        return [
            'id' => $this->id,
            'usuario_id' => $this->usuario_id,
            'token_hash' => $this->token_hash, // Agora usa token_hash
            'expire_at' => $this->expire_at,
            'usado' => (int)$this->usado // Converte bool para int (0 ou 1) para o DB
        ];
    }

    public function save() : int|bool {
        $passwordResetsDAO = new PasswordResetsDAO();

        $data = $this->toArray();
        $id = $this->getId();

        if ($id === null) {
            // --- CREATE (Inserir) ---
            $newId = $passwordResetsDAO->create($data);
            $this->setId($newId);
            return $newId;
            
        } else {
            // --- UPDATE (Atualizar) ---
            unset($data['id']); 
            
            return $passwordResetsDAO->update($id, $data);
        }
    }
}