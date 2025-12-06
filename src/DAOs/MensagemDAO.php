<?php 

namespace DAOs;

use DAOs\BaseDAO;

class MensagemDAO extends BaseDAO {
    public function __construct() {
        parent::__construct('mensagem');
    }
}
?>