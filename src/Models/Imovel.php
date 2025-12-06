<?php 

class Imovel {
    private $id;
    private $nome;
    private $usuario_id;
    private $endereco_id;
    private $tipo_imovel;
    private $valor;
    private $area;
    private $descricao;
    private $quant_quartos;
    private $quant_suites;
    private $quant_cozinhas;
    private $quant_banheiros;
    private $quant_piscinas;
    private $vagas_garagem;
    private $status;
    private $mobiliado;
    private $data_cad;
    private $data_atualizacao;

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
}

?>