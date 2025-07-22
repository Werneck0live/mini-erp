<?php

require_once 'config/bd.php';

class BaseModel {
    protected $pdo;
    protected $table;
    
    public function __construct() {
        $this->pdo = DB::getConnection();
        $this->table = $this->getTable();
    }

    private function getTable() {
        // Retorna o nome da classe filha em minúsculas e no plural
        $className = get_called_class();
        return strtolower($className) . 's';
    }

    public function listarTodos() {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE `status` <> 'inativo'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function deletar($id) {
        return $this->pdo->prepare("UPDATE {$this->table} SET `status` = 'inativo' WHERE id = ?")->execute([$id]);
    }

    public function obterPorId($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

?>