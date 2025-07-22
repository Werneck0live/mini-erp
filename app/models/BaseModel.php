<?php

require_once '../config/databases/mysql_config.php';

class BaseModel {
    protected $pdo;
    protected $table;
    
    public function __construct() {
        $this->pdo = MySQLDatabase::getConnection();
        $this->table = $this->getTable();
    }

    private function getTable() {
        $className = get_called_class();
          // Pluraliza o nome da classe

        // Verifica se o nome da tabela termina com "m" e substitui por "ns"
        if (substr($className, -1) === 'm') {
            $table = strtolower(substr($className, 0, -1) . 'ns');
        } else {
            $table = strtolower($className) . 's';
        }

        return $table;
    }

    public function beginTransaction() {
        $this->pdo->beginTransaction();
    }

    public function commit() {
        $this->pdo->commit();
    }

    public function rollBack() {
        $this->pdo->rollBack();
    }

    public function listarTodos() {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table}");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarTodosNaoInativos() {
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