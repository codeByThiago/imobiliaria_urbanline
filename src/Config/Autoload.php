<?php 

namespace Config;

class Autoload {
    public static function register() {
        spl_autoload_register(function($className) {
            $arquivo = BASE_DIR . str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';

            if (file_exists($arquivo)) {
                require $arquivo;
                return true;
            } return false;
        });
    }
}

?>