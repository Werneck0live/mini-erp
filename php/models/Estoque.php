<?php

require_once 'models/BaseModel.php';

class Estoque extends BaseModel{

    public function atualizarEstoque($produtoId, $quantidade) {
        $stmt = $this->pdo->prepare("UPDATE estoque SET quantidade = ? WHERE produto_id = ?");
        return $stmt->execute([$quantidade, $produtoId]);
    }

    public function obterEstoque($produtoId) {
        $stmt = $this->pdo->prepare("SELECT quantidade FROM estoque WHERE produto_id = ?");
        $stmt->execute([$produtoId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function adicionarEstoque($produtoId, $quantidade) {
        $stmt = $this->pdo->prepare("INSERT INTO estoque (produto_id, quantidade) VALUES (?,?)");
        return $stmt->execute([$produtoId, $quantidade]);
    }
}
