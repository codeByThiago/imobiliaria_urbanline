<?php 

class Role {
    private $id;
    private $nome;

    // GETTERS E SETTERS

    public function __construct($data = []) {
        $this->id = $data['id'];
        $this->nome = $data['nome'];
    }
    
    public function setId($id) { return $this->id = $id; }
    public function setNome($nome) { return $this->nome = $nome; }

    public function getId() { return $this->id; }
    public function getNome() { return $this->nome; }
}

?>