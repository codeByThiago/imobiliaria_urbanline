<?php 

namespace Core;

use Exception;

class Autoload {
    public static function register() {
        try {
            spl_autoload_register(function($className) {
                $fileName = BASE_DIR . "src" . DIRECTORY_SEPARATOR . str_replace("\\", '/', $className) . ".php";

                if(file_exists($fileName)) {
                    require_once $fileName;
                }
            });
        } catch (Exception $e) {
            throw new Exception("Erro: " . $e->getMessage());
        }
    }
}

?>