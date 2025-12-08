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

    public function findWithPrimaryPhoto(array $filters, int $limit, int $offset): array {
        
        // --- 1. Construção da cláusula WHERE e Bindings (REUTILIZÁVEL) ---
        $whereClauses = [];
        $bindings = [];
        // (Copie aqui a lógica de construção de WHERE e $bindings da sua função original)
        // Exemplo:
        if (isset($filters['status-imovel'])) {
            $whereClauses[] = "i.status = :status_imovel";
            $bindings[':status_imovel'] = $filters['status-imovel'];
        }
        if (isset($filters['tipo'])) {
            $whereClauses[] = "i.tipo_imovel = :tipo";
            $bindings[':tipo'] = $filters['tipo'];
        }
        if (isset($filters['quartos'])) {
             if ($filters['quartos'] == '4+') {
                 $whereClauses[] = "i.quant_quartos >= 4";
             } else {
                 $whereClauses[] = "i.quant_quartos = :quartos";
                 $bindings[':quartos'] = $filters['quartos'];
             }
        }
        // ... (Inclua os outros filtros) ...

        $whereSql = !empty($whereClauses) ? " WHERE " . implode(" AND ", $whereClauses) : "";
        
        // --- 2. Contar o Total de Imóveis (sem paginação) ---
        $countSql = "SELECT COUNT(i.id) FROM {$this->table} i" . $whereSql;
        $stmtCount = $this->conexao->prepare($countSql);
        foreach ($bindings as $key => $value) {
            $stmtCount->bindValue($key, $value);
        }
        $stmtCount->execute();
        $totalImoveis = $stmtCount->fetchColumn();


        // --- 3. Buscar Imóveis com Limite e Offset ---
        $orderBy = "i.id DESC";
        if (isset($filters['ordenar-por'])) {
            $orderBy = $this->buildOrderByClause($filters['ordenar-por']);
        }

        // CORREÇÃO: Usar MIN(f.url) para satisfazer o ONLY_FULL_GROUP_BY
        $sql = "SELECT i.*, MIN(f.url) AS foto_url 
                FROM {$this->table} i
                LEFT JOIN imovel_fotos f ON f.imovel_id = i.id "
                . $whereSql .
                " GROUP BY i.id 
                ORDER BY " . $orderBy . 
                " LIMIT :limit OFFSET :offset";
                
        try {
            $stmt = $this->conexao->prepare($sql);
            
            // Bind dos filtros
            foreach ($bindings as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            
            // Bind do LIMIT e OFFSET
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Mapeamento (simplificado, já que usamos GROUP BY i.id)
            $imoveis = [];
            $fotos_por_imovel = [];
            
            foreach ($results as $row) {
                 $imoveis[] = $row; 
                 // Não é necessário o in_array/processedImovelIds pois o GROUP BY já garante a unicidade
                if (!empty($row['foto_url'])) {
                    $fotos_por_imovel[$row['id']] = $row['foto_url']; 
                }
            }

            return [
                'imoveis' => $imoveis,
                'fotos_por_imovel' => $fotos_por_imovel,
                'total_imoveis' => $totalImoveis // Retorna o total para a paginação
            ];

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