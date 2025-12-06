<?php

namespace Models;

use DAOs\EnderecoDAO;

class Endereco {
    private ?int $id;
    private string $cep;
    private string $uf;
    private string $cidade;
    private string $bairro;
    private string $logradouro;
    private string $numero;

    public function __construct(array $data = []) {
        $this->id = $data['id'] ?? null;
        $this->cep = $data['cep'] ?? '';
        $this->uf = $data['uf'] ?? '';
        $this->cidade = $data['cidade'] ?? '';
        $this->bairro = $data['bairro'] ?? '';
        $this->logradouro = $data['logradouro'] ?? '';
        $this->numero = $data['numero'] ?? '';
    }

    // Getters e setters
    public function getId(): ?int { return $this->id; }
    public function getCep(): string { return $this->cep; }
    public function getUf(): string { return $this->uf; }
    public function getCidade(): string { return $this->cidade; }
    public function getBairro(): string { return $this->bairro; }
    public function getLogradouro(): string { return $this->logradouro; }
    public function getNumero(): string { return $this->numero; }
    
    public function setId(?int $id): void { $this->id = $id; }
    public function setCep(string $cep): void { $this->cep = $cep; }
    public function setUf(string $uf): void { $this->uf = $uf; }
    public function setCidade(string $cidade): void { $this->cidade = $cidade; }
    public function setBairro(string $bairro): void { $this->bairro = $bairro; }
    public function setLogradouro(string $logradouro): void { $this->logradouro = $logradouro; }
    public function setNumero(string $numero): void { $this->numero = $numero; }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'cep' => $this->cep,
            'uf' => $this->uf,
            'cidade' => $this->cidade,
            'bairro' => $this->bairro,
            'logradouro' => $this->logradouro,
            'numero' => $this->numero,
        ];
    }   

    public function save() : int|bool {
        // 1. Instancia o DAO
        $enderecoDAO = new EnderecoDAO();
        
        // 2. Obtém os dados do objeto em formato de array
        $data = $this->toArray();
        $id = $this->getId();
        
        // 3. Verifica se é CREATE ou UPDATE
        if ($id === null) {
            // --- CREATE (Inserir) ---
            $newId = $enderecoDAO->create($data);
            
            // É crucial atualizar o ID do objeto após a inserção
            $this->setId($newId);
            
            return $newId;
            
        } else {
            unset($data['id']); 
            
            return $enderecoDAO->update($id, $data);
        }
    }
}

?>
