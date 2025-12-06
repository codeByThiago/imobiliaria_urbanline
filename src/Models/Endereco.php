<?php

namespace Models;

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

    public function setCep(string $cep): void { $this->cep = $cep; }
    public function setUf(string $uf): void { $this->uf = $uf; }
    public function setCidade(string $cidade): void { $this->cidade = $cidade; }
    public function setBairro(string $bairro): void { $this->bairro = $bairro; }
    public function setLogradouro(string $logradouro): void { $this->logradouro = $logradouro; }
    public function setNumero(string $numero): void { $this->numero = $numero; }
}

?>
