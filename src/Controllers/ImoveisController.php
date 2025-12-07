<?php

namespace Controllers;

use DAOs\EnderecoDAO;
use DAOs\ImoveisDAO;
use DAOs\ImovelFotosDAO;
use Models\Imovel; 
use Models\ImovelFotos;

class ImoveisController {
    private ImoveisDAO $imoveisDAO;
    private ImovelFotosDAO $imovelFotosDAO;
    
    public function __construct() {
        $this->imoveisDAO = new ImoveisDAO();
        $this->imovelFotosDAO = new ImovelFotosDAO();
    }
    
    public function search() {
        // 1. Coleta e sanitiza os filtros de $_GET
        $filters = $this->sanitizeFilters($_GET);
        
        // 2. Chama o NOVO método do DAO com os filtros
        // Este método retorna: ['imoveis' => [...], 'fotos_por_imovel' => [...]]
        $results = $this->imoveisDAO->findWithPrimaryPhoto($filters);
        
        // Garante que os dados são arrays vazios se nada for encontrado
        $imoveis = $results['imoveis'] ?? [];
        $fotos_por_imovel = $results['fotos_por_imovel'] ?? [];

        // 3. Renderiza a View
        renderView('imovel/procura-imoveis', [
            'imoveis' => $imoveis, 
            'fotos_por_imovel' => $fotos_por_imovel,
            'filters' => $filters // Passa os filtros sanitizados para a View
        ]);
    }

    /**
     * Função helper para sanitizar e validar os filtros.
     */
    private function sanitizeFilters(array $input): array {
        $safeFilters = [];
        
        // Exemplo de sanitização (pode ser mais detalhada)
        $safeFilters['status-imovel'] = filter_var($input['status-imovel'] ?? '');
        
        // Valores numéricos devem ser validados como INT
        $safeFilters['quartos'] = filter_var($input['quartos'] ?? null, FILTER_VALIDATE_INT);
        // ... adicione todos os filtros aqui ...
        
        return array_filter($safeFilters, fn($value) => $value !== null && $value !== '');
    }
}