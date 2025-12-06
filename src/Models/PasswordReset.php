<?php

class PasswordReset {
    private $id;
    private $usuario_id;
    private $token;
    private $expire_at;
    private $usado;

    // GETTERS E SETTERS

    public function __construct($data = []) {
        $this->id = $data['id'];
        $this->usuario_id = $data['usuario_id'];
        $this->token = $data['token'];
        $this->expire_at = $data['expire_at'];
        $this->usado = $data['usado'];
    }

    public function setId($id) { return $this->id = $id; }
    public function setUsuarioId($usuario_id) { return $this->usuario_id = $usuario_id; }
    public function setToken($token) { return $this->token = $token; }
    public function setExpireAt($expire_at) { return $this->expire_at = $expire_at; }
    public function setUsado($usado) { return $this->usado = $usado; }
    
    public function getId() { return $this->id; }
    public function getUsuarioId() { return $this->usuario_id; }
    public function getToken() { return $this->token; }
    public function getExpireAt() { return $this->expire_at; }
    public function getUsado() { return $this->usado; }
}

?>