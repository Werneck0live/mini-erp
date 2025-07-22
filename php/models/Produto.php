<?php

require_once 'models/BaseModel.php';

class Produto  extends BaseModel{

    public function criar($nome, $preco, $variacoes) {
        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (nome, preco, variacoes) VALUES (?, ?, ?)");
        if($stmt->execute([$nome, $preco, $variacoes])){
            $ultimoId = $this->pdo->lastInsertId();
            return $ultimoId;            
        } else {
            return 0;
        }
    }
    
    public function listar() {
        $stmt = $this->pdo->query("SELECT * FROM produtos");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function atualizar($id, $nome, $preco, $variacoes) {
        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET nome = ?, preco = ?, variacoes = ? WHERE id = ?");
        return $stmt->execute([$nome, $preco, $variacoes, $id]);
    }

    public function obterPorId($id) {
        $stmt = $this->pdo->prepare("SELECT p.*, e.quantidade FROM {$this->table} p JOIN estoque e ON p.id = e.produto_id WHERE p.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
