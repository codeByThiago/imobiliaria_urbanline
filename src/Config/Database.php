<?php
namespace Config;

use PDO;
use PDOException;

class Database {
    protected static $conexao = null;

    public function __construct() {
        if (self::$conexao === null) {
            $dsn = "mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_NAME'] . ";charset=utf8mb4";
            try {
                self::$conexao = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS']);
                self::$conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Erro de conexão: " . $e->getMessage());
            }
        }
    }

    protected function getConnection(): PDO {
        return self::$conexao;
    }
}