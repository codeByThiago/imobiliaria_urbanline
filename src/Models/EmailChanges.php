<?php 

namespace Models;

use DAOs\EmailChangesDAO; // Assumindo que você tem este DAO

class EmailChanges {
    private ?int $id;
    private ?int $user_id;
    private string $new_email;
    private string $token_hash;
    private string $expires_at;
    
    // Propriedade temporária para armazenar o token puro a ser enviado
    private string $plain_token; 

    public function __construct(int $user_id, string $new_email) {
        $this->id = null;
        $this->user_id = $user_id;
        $this->new_email = $new_email;
        
        // --- Geração de Token ---
        // 1. Gera o token puro (para envio por e-mail)
        $this->plain_token = bin2hex(random_bytes(32));
        
        // 2. Armazena apenas o HASH no objeto (e no banco de dados)
        $this->token_hash = hash('sha256', $this->plain_token);

        // Define a expiração para 1 hora (3600 segundos)
        $this->expires_at = date('Y-m-d H:i:s', time() + 3600);
    }
    
    // --- GETTERS e SETTERS ---

    public function getId(): ?int { return $this->id; }
    public function getUserId(): ?int { return $this->user_id; }
    public function getNewEmail(): string { return $this->new_email; }
    public function getTokenHash(): string { return $this->token_hash; }
    public function getExpiresAt(): string { return $this->expires_at; }
    public function getPlainToken(): string { return $this->plain_token; }

    public function setId(?int $id): void { $this->id = $id; }
    public function setUserId(?int $user_id): void { $this->user_id = $user_id; }
    public function setNewEmail(string $new_email): void { $this->new_email = $new_email; }
    public function setTokenHash(string $token_hash): void { $this->token_hash = $token_hash; }
    public function setExpiresAt(string $expires_at): void { $this->expires_at = $expires_at; }

    // --- MÉTODOS DE PERSISTÊNCIA ---

    public function toArray() : array {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'new_email' => $this->new_email,
            'token_hash' => $this->token_hash,
            'expires_at' => $this->expires_at,
        ];
    }
    
    public function save() : int|bool {
        $emailChangesDAO = new EmailChangesDAO();
        
        $data = $this->toArray();
        $id = $this->getId();
        
        if ($id === null) {
            // --- CREATE (Inserir) ---
            $newId = $emailChangesDAO->create($data);
            $this->setId($newId);
            return $newId;
            
        } else {
            // --- UPDATE (Atualizar) ---
            unset($data['id']); 
            return $emailChangesDAO->update($id, $data);
        }
    }
}