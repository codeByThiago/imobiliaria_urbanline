<?php 
namespace Core;

use PDO;
use PDOException;

class Database {
    protected static ?PDO $conn = null;

    public function __construct() {
        if(self::$conn === null) {
            try {
                $dsn = "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};charset=utf8mb4";
                self::$conn = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS']);
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                throw new \Exception("Database connection error: " . $e->getMessage());
            }
        }
    }

    public function getConnection() : PDO {
        return self::$conn;
    }
}

?>