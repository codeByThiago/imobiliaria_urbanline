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

    public function listAllById(int $userId): array {
        $sql = "
            SELECT 
                i.id, 
                i.nome, 
                i.tipo_imovel, 
                i.condicao,
                i.valor, 
                i.area, 
                i.status,
                e.cidade, 
                e.uf,
                (SELECT url FROM imovel_fotos WHERE imovel_id = i.id ORDER BY id ASC LIMIT 1) as foto_principal
            FROM 
                imoveis i
            JOIN 
                endereco e ON i.endereco_id = e.id
            WHERE 
                i.usuario_id = :user_id
            ORDER BY 
                i.data_cad DESC
        ";
        
        try {
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("DAO Error (findByProprietarioWithPrimaryPhoto): " . $e->getMessage());
            // Em caso de erro, retorna um array vazio para não quebrar a view
            return []; 
        }
    }

    public function findWithPrimaryPhoto(array $filters, int $limit, int $offset): array {
        
        // --- 1. Construção da cláusula WHERE e Bindings (REUTILIZÁVEL) ---
        $whereClauses = [];
        $bindings = [];

        // Filtro de Busca (search-input) - Assumindo que busca em nome ou descrição
        if (isset($filters['search-input'])) {
            $searchTerm = "%" . $filters['search-input'] . "%";
            $whereClauses[] = "(i.nome LIKE :search_term OR i.descricao LIKE :search_term)";
            $bindings[':search_term'] = $searchTerm;
        }

        // Filtro de Status do Imóvel
        if (isset($filters['status-imovel'])) {
            $whereClauses[] = "i.status = :status_imovel";
            $bindings[':status_imovel'] = $filters['status-imovel'];
        }

        // Filtro de Tipo de Imóvel (Você já corrigiu para i.tipo_imovel)
        if (isset($filters['tipo'])) {
            $whereClauses[] = "i.tipo_imovel = :tipo";
            $bindings[':tipo'] = $filters['tipo'];
        }

        // Filtro de Faixa de Valor
        if (isset($filters['valor'])) {
            // É necessário implementar esta função no DAO ou Controller
            [$min, $max] = $this->parseValueRange($filters['valor']);
            
            if ($min !== null) {
                $whereClauses[] = "i.valor >= :min_valor";
                $bindings[':min_valor'] = $min;
            }
            if ($max !== null) {
                $whereClauses[] = "i.valor <= :max_valor";
                $bindings[':max_valor'] = $max;
            }
        }

        // Helper para filtros de quantidade (quartos, banheiros, etc.)
        $this->addQuantityFilter($whereClauses, $bindings, $filters, 'quartos', 'quant_quartos');
        $this->addQuantityFilter($whereClauses, $bindings, $filters, 'banheiros', 'quant_banheiros');
        $this->addQuantityFilter($whereClauses, $bindings, $filters, 'cozinhas', 'quant_cozinhas');
        $this->addQuantityFilter($whereClauses, $bindings, $filters, 'piscinas', 'quant_piscinas');
        $this->addQuantityFilter($whereClauses, $bindings, $filters, 'vagas-de-garagem', 'vagas_garagem');

        if (isset($filters['mobiliado']) && ($filters['mobiliado'] === 1 || $filters['mobiliado'] === 0)) {
            $whereClauses[] = "i.mobiliado = :mobiliado_val";
            $bindings[':mobiliado_val'] = $filters['mobiliado'];
        }
        
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

    public function salvarFotos(int $imovelId, array $fotos): bool {
        if (empty($fotos)) {
            return true;
        }

        // Prepara a consulta de inserção para a tabela 'imovel_fotos'
        $sql = "INSERT INTO imovel_fotos (imovel_id, url) VALUES (:imovel_id, :url)";
        
        try {
            $this->conexao->beginTransaction();
            $stmt = $this->conexao->prepare($sql);

            foreach ($fotos as $url) {
                $stmt->bindValue(':imovel_id', $imovelId, PDO::PARAM_INT);
                $stmt->bindValue(':url', $url, PDO::PARAM_STR);
                $stmt->execute();
            }

            $this->conexao->commit();
            return true;

        } catch (PDOException $e) {
            $this->conexao->rollBack();
            // Lançar exceção para ser tratada no Controller
            throw new Exception("Erro ao salvar as fotos: " . $e->getMessage());
        }
    }

    private function addQuantityFilter(array &$whereClauses, array &$bindings, array $filters, string $filterKey, string $columnName): void {
        if (isset($filters[$filterKey]) && !empty($filters[$filterKey])) { // Adicionado !empty()
            $filterValue = $filters[$filterKey];
            $bindKey = ":{$columnName}_val";
            
            if (str_ends_with((string)$filterValue, '+')) {
                // Caso '4+', '2+', etc.
                $minVal = (int) str_replace('+', '', $filterValue);
                // Usamos bindValue para o limite
                $whereClauses[] = "i.{$columnName} >= {$bindKey}";
                $bindings[$bindKey] = $minVal;

            } else {
                // Caso exato '1', '2', '3'
                $whereClauses[] = "i.{$columnName} = {$bindKey}";
                $bindings[$bindKey] = (int) $filterValue;
            }
        }
    }

    private function parseValueRange(string $range): array {
        if ($range === '1m+') {
            return [1000000, null];
        }
        
        $parts = explode('-', $range);
        
        if (count($parts) === 2) {
            $minStr = str_replace('k', '000', $parts[0]);
            $maxStr = str_replace('k', '000', $parts[1]);
            
            $min = (int) $minStr;
            $max = (int) $maxStr;
            
            return [$min, $max];
        }
        
        // Para o caso '0-100k'
        if ($range === '0-100k') {
            return [0, 100000];
        }
        
        return [null, null]; // Valor inválido
    }
    
    private function buildOrderByClause(string $sort): string {
        return match ($sort) {
            'menor-preco' => 'i.valor ASC',
            'maior-preco' => 'i.valor DESC',
            default => 'i.id DESC', // Relevância/Mais recente
        };
    }

    public function verificarProprietario(int $imovelId, int $proprietarioId) {
        try {
            $sql = "SELECT COUNT(*) FROM {$this->table} WHERE id = :imovel_id AND usuario_id = :proprietario_id";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(':imovel_id', $imovelId, PDO::PARAM_INT);
            $stmt->bindValue(':proprietario_id', $proprietarioId, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            throw new Exception("Erro ao verificar proprietário do imóvel: " . $e->getMessage());
        }
    }

    public function countImoveisByUser(int $userId): int {
        $sql = "SELECT COUNT(id) FROM imoveis WHERE usuario_id = :user_id";
        
        try {
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            
            // Retorna o resultado da contagem (primeira coluna da primeira linha)
            return (int)$stmt->fetchColumn(); 

        } catch (PDOException $e) {
            // Em ambiente de produção, logar o erro em vez de exibi-lo
            error_log("Erro ao contar imóveis: " . $e->getMessage());
            return 0;
        }
    }
    
    public function countImoveisByStatus(int $userId, string $status): int {
        $sql = "SELECT COUNT(id) FROM imoveis WHERE usuario_id = :user_id AND status = :status";
        
        try {
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
            $stmt->execute();
            
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Erro ao contar imóveis por status: " . $e->getMessage());
            return 0;
        }
    }
    
    
    public function countImoveisCadastradosNoMes(int $userId): int {
        // Usa as funções de data do MySQL/SQLite para filtrar pelo mês e ano atuais
        $sql = "SELECT COUNT(id) FROM imoveis 
                WHERE usuario_id = :user_id 
                AND MONTH(data_cad) = MONTH(CURRENT_DATE())
                AND YEAR(data_cad) = YEAR(CURRENT_DATE())";
        
        try {
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Erro ao contar cadastros mensais: " . $e->getMessage());
            return 0;
        }
    }
}
?>