<?php 

class Permissao {
    private $id;
    private $nome;
    private $descricao;

    // GETTERS E SETTERS

    public function __construct($data = []) {
        $this->id = $data['id'];
        $this->nome = $data['nome'];
        $this->descricao = $data['descricao'];
    }
    
    public function setId($id) { return $this->id = $id; }
    public function setNome($nome) { return $this->nome = $nome; }
    public function setDescricao($descricao) { return $this->descricao = $descricao; }

    public function getId() { return $this->id; }
    public function getNome() { return $this->nome; }
    public function getDescricao() { return $this->descricao; }
}

?>