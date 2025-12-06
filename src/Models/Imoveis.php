<?php 

namespace Models;

use DAOs\ImoveisDAO;

class Imoveis {
    private ?int $id;
    private string $nome;
    private ?int $usuario_id;
    private ?int $endereco_id;
    private string $tipo_imovel;
    private float $valor;
    private float $area;
    private string $descricao;
    private int $quant_quartos;
    private int $quant_suites;
    private int $quant_cozinhas;
    private int $quant_banheiros;
    private int $quant_piscinas;
    private int $vagas_garagem;
    private string $status;
    private bool $mobiliado;
    private string $data_cad;
    private string $data_atualizacao;

    // GETTERS E SETTERS

    public function __construct($data = []) {
        $this->id = $data['id'] ?? NULL;
        $this->nome = $data['nome'] ?? '';
        $this->usuario_id = $data['usuario_id'] ?? NULL;
        $this->endereco_id = $data['endereco_id'] ?? NULL;
        $this->tipo_imovel = $data['tipo_imovel'] ?? '';
        $this->valor = $data['valor'] ?? '';
        $this->area = $data['area'] ?? '';
        $this->descricao = $data['descricao'] ?? '';
        $this->quant_quartos = $data['quan_quartos'] ?? '';
        $this->quant_suites = $data['quant_suites'] ?? '';
        $this->quant_cozinhas = $data['quant_cozinhas'] ?? '';
        $this->quant_banheiros = $data['quant_banheiros'] ?? '';
        $this->quant_piscinas = $data['quant_piscinas'] ?? '';
        $this->vagas_garagem = $data['vagas_garagem'] ?? '';
        $this->status = $data['status'] ?? '';
        $this->mobiliado = $data['mobiliado'] ?? '';
        $this->data_cad = $data['data_cad'] ?? '';
        $this->data_atualizacao = $data['data_atualizacao'] ?? '';
    }

    public function setId($id) { return $this->id = $id; }
    public function setNome($nome) { return $this->nome = $nome; }
    public function setUsuarioId($usuario_id) { return $this->usuario_id = $usuario_id; }
    public function setEnderecoId($endereco_id) { return $this->endereco_id = $endereco_id; }
    public function setTipoImovel($tipo_imovel) { return $this->tipo_imovel = $tipo_imovel; }
    public function setValor($valor) { return $this->valor = $valor; }
    public function setArea($area) { return $this->area = $area; }
    public function setDescricao($descricao) { return $this->descricao = $descricao; }
    public function setQuanQuartos($quan_quartos) { return $this->quant_quartos = $quan_quartos; }
    public function setQuantSuites($quant_suites) { return $this->quant_suites = $quant_suites; }
    public function setQuanCozinhas($quan_cozinhas) { return $this->quant_cozinhas = $quan_cozinhas; }
    public function setQuanBanheiros($quan_banheiros) { return $this->quant_banheiros = $quan_banheiros; }
    public function setQuanPiscinas($quan_piscinas) { return $this->quant_piscinas = $quan_piscinas; }
    public function setVagasGaragem($vagas_garagem) { return $this->vagas_garagem = $vagas_garagem; }
    public function setStatus($status) { return $this->status = $status; }
    public function setMobiliado($mobiliado) { return $this->mobiliado = $mobiliado; }
    public function setDataCadastro($data_cad) { return $this->data_cad = $data_cad; }
    public function setDataAtualizacao($data_atualizacao) { return $this->data_atualizacao = $data_atualizacao; }

    public function getId() { return $this->id; }
    public function getNome() { return $this->nome; }
    public function getUsuarioId() { return $this->usuario_id; }
    public function getEnderecoId() { return $this->endereco_id; }
    public function getTipoImovel() { return $this->tipo_imovel; }
    public function getValor() { return $this->valor; }
    public function getArea() { return $this->area; }
    public function getDescricao() { return $this->descricao; }
    public function getQuantQuartos() { return $this->quant_quartos; }
    public function getQuantSuites() { return $this->quant_suites; }
    public function getQuantCozinhas() { return $this->quant_cozinhas; }
    public function getQuantBanheiros() { return $this->quant_banheiros; }
    public function getQuantPiscinas() { return $this->quant_piscinas; }
    public function getVagasGaragem() { return $this->vagas_garagem; }
    public function getStatus() { return $this->status; }
    public function getMobiliado() { return $this->mobiliado; }
    public function getDataCadastro() { return $this->data_cad; }
    public function getDataAtualizacao() { return $this->data_atualizacao; }
    
    // Funcões e Métodos Importantes
    public function toArray() : array {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'usuario_id' => $this->usuario_id,
            'endereco_id' => $this->endereco_id,
            'tipo_imovel' => $this->tipo_imovel,
            'valor' => $this->valor,
            'area' => $this->area,
            'descricao' => $this->descricao,
            'quant_quartos' => $this->quant_quartos,
            'quant_suites' => $this->quant_suites,
            'quant_cozinhas' => $this->quant_cozinhas,
            'quant_banheiros' => $this->quant_banheiros,
            'quant_piscinas' => $this->quant_piscinas,
            'vagas_garagem' => $this->vagas_garagem,
            'status' => $this->status,
            'mobiliado' => $this->mobiliado,
            'data_cad' => $this->data_cad,
            'data_atualizacao' => $this->data_atualizacao,
        ];
    }

    public function save() : int|bool {
        
        $imoveisDAO = new ImoveisDAO();

        $data = $this->toArray();
        $id = $this->getId();

        if ($id === null) {
            // --- CREATE (Inserir) ---
            $newId = $imoveisDAO->create($data);
            
            // É crucial atualizar o ID do objeto após a inserção
            $this->setId($newId);
            return $newId;
            
        } else {
            // --- UPDATE (Atualizar) ---
            
            // O update do BaseDAO exige o ID e os dados
            
            // Remove o ID do array de dados para não tentar atualizar a PK
            unset($data['id']); 
            
            return $imoveisDAO->update($id, $data);
        }
    }
}

?>