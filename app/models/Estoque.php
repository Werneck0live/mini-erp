<?php

require_once '../models/BaseModel.php';

class Estoque extends BaseModel{

    protected $table = 'estoque';

    public function atualizarEstoque($produtoId, $quantidade) {
        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET quantidade = ? WHERE produto_id = ?");
        return $stmt->execute([$quantidade, $produtoId]);
    }

    public function obterEstoque($produtoId) {
        $stmt = $this->pdo->prepare("SELECT quantidade FROM {$this->table} WHERE produto_id = ?");
        $stmt->execute([$produtoId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function adicionarEstoque($produtoId, $quantidade) {
        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (produto_id, quantidade) VALUES (?,?)");
        return $stmt->execute([$produtoId, $quantidade]);
    }

    public function deletar($produtoId) {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE produto_id = ?");
        return $stmt->execute([$produtoId]);
    }
}
