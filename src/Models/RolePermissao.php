<?php 

class RolePermissao {
    private $id;
    private $role_id;
    private $permissao_id;

    // GETTERS E SETTERS

    public function __construct($data = [null]) {
        $this->id = $data['id'];
        $this->role_id = $data['role_id'];
        $this->permissao_id = $data['permissao_id'];
    }

    public function setId($id) { return $this->id = $id; }
    public function setRoleId($role_id) { return $this->role_id = $role_id; }
    public function setPermissaoId($permissao_id) { return $this->permissao_id = $permissao_id; }

    public function getId() { return $this->id; }
    public function getRoleId() { return $this->role_id; }
    public function getPermissaoId() { return $this->permissao_id; }

}

?>