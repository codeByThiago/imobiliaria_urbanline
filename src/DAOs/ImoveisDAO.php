<?php

namespace DAOs;

use DAOs\BaseDAO;
use Exception;
use PDO;
use PDOException;

class ImoveisDAO extends BaseDAO {
    
    public function __construct() {
        parent::__construct("imoveis"); // Sua tabela principal é 'imoveis'
    }

    /**
     * Busca imóveis aplicando filtros dinâmicos e incluindo a URL da primeira foto.
     * @param array $filters Filtros recebidos do Controller.
     * @return array Contendo 'imoveis' e 'fotos_por_imovel'.
     */
    public function findWithPrimaryPhoto(array $filters): array {
        $sql = "SELECT i.*, f.url AS foto_url, f.imovel_id AS foto_imovel_id
                FROM {$this->table} i
                -- LEFT JOIN para pegar a primeira foto de cada imóvel
                LEFT JOIN imovel_fotos f ON f.imovel_id = i.id";
                
        $whereClauses = [];
        $bindings = [];
        $orderBy = "i.id DESC"; // Default

        // 1. Processar Filtros de WHERE
        if (isset($filters['status-imovel'])) {
            $whereClauses[] = "i.status = :status_imovel";
            $bindings[':status_imovel'] = $filters['status-imovel'];
        }
        
        if (isset($filters['tipo'])) {
            $whereClauses[] = "i.tipo = :tipo";
            $bindings[':tipo'] = $filters['tipo'];
        }

        // Filtros Numéricos (Quartos, Banheiros, etc.)
        if (isset($filters['quartos'])) {
            // Se o valor for '4+', você precisa ajustar a query. Ex:
            if ($filters['quartos'] == '4+') {
                $whereClauses[] = "i.quant_quartos >= 4";
            } else {
                $whereClauses[] = "i.quant_quartos = :quartos";
                $bindings[':quartos'] = $filters['quartos'];
            }
        }
        
        // ... Implemente os filtros para 'banheiros', 'cozinhas', 'piscinas', 'vagas-de-garagem' ...
        
        // 2. Processar Filtro de Faixa de Valor (Exemplo Simples)
        if (isset($filters['valor'])) {
            // Lógica para converter '100k-300k' em min/max
            list($min, $max) = $this->parseValueRange($filters['valor']);
            if ($min !== null) {
                $whereClauses[] = "i.valor >= :min_valor";
                $bindings[':min_valor'] = $min;
            }
            if ($max !== null) {
                $whereClauses[] = "i.valor <= :max_valor";
                $bindings[':max_valor'] = $max;
            }
        }
        
        // 3. Montar a Cláusula WHERE
        if (!empty($whereClauses)) {
            $sql .= " WHERE " . implode(" AND ", $whereClauses);
        }

        // 4. Processar Ordenação
        if (isset($filters['ordenar-por'])) {
            $orderBy = $this->buildOrderByClause($filters['ordenar-por']);
        }
        $sql .= " ORDER BY " . $orderBy;

        // 5. Execução
        try {
            $stmt = $this->conexao->prepare($sql);
            foreach ($bindings as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Mapeamento e Agrupamento no DAO para retornar limpo ao Controller
            return $this->mapResultsToModelsAndPhotos($results);

        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar imóveis: " . $e->getMessage());
        }
    }
    
    // --- Funções Auxiliares (Helper Functions) ---

    private function parseValueRange(string $range): array {
        // Implementar a lógica de conversão aqui (ex: '100k-300k' => [100000, 300000])
        // Retorna [min, max]
        return [null, null]; // Implementação placeholder
    }

    private function buildOrderByClause(string $sort): string {
        return match ($sort) {
            'menor-preco' => 'i.valor ASC',
            'maior-preco' => 'i.valor DESC',
            default => 'i.id DESC', // Relevância/Mais recente
        };
    }
    
    private function mapResultsToModelsAndPhotos(array $results): array {
        $imoveis = [];
        $fotos_por_imovel = [];
        $processedImovelIds = [];
        
        // Loop que garante que cada imóvel apareça uma vez e pega a primeira foto
        foreach ($results as $row) {
            $imovelId = $row['id'];

            // Se ainda não processamos este imóvel, o adicionamos
            if (!in_array($imovelId, $processedImovelIds)) {
                $imoveis[] = $row; // Adiciona o imóvel
                $processedImovelIds[] = $imovelId;
            }
            
            // Pega a URL da foto (se a consulta trouxer a primeira foto como a primeira linha)
            if (!empty($row['foto_url']) && !isset($fotos_por_imovel[$imovelId])) {
                 // Usa a foto_url vinda do JOIN
                $fotos_por_imovel[$imovelId] = $row['foto_url']; 
            }
        }
        
        return [
            'imoveis' => $imoveis,
            'fotos_por_imovel' => $fotos_por_imovel
        ];
    }
}
?>