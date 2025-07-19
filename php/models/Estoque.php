<?php
class Estoque {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function atualizarEstoque($produto_id, $quantidade) {
        $stmt = $this->pdo->prepare("UPDATE estoque SET quantidade = quantidade - ? WHERE produto_id = ?");
        return $stmt->execute([$quantidade, $produto_id]);
    }

    public function obterEstoque($produto_id) {
        $stmt = $this->pdo->prepare("SELECT quantidade FROM estoque WHERE produto_id = ?");
        $stmt->execute([$produto_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function adicionarEstoque($produto_id, $quantidade) {
        $stmt = $this->pdo->prepare("UPDATE estoque SET quantidade = quantidade + ? WHERE produto_id = ?");
        return $stmt->execute([$quantidade, $produto_id]);
    }
}
