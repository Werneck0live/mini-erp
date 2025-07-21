<?php

require_once 'config/bd.php';

class Produto {
    private $pdo;

    public function __construct() {        
        $pdo = DB::getConnection();
        $this->pdo = $pdo;

    }
    public function criar($nome, $preco, $variacoes) {
        $stmt = $this->pdo->prepare("INSERT INTO produtos (nome, preco, variacoes) VALUES (?, ?, ?)");
        $stmt->execute([$nome, $preco, $variacoes]);
        $ultimoId = $this->pdo->lastInsertId();
        return $ultimoId;
    }

    public function listar() {
        $stmt = $this->pdo->query("SELECT * FROM produtos");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function atualizar($id, $nome, $preco, $variacoes) {
        $stmt = $this->pdo->prepare("UPDATE produtos SET nome = ?, preco = ?, variacoes = ? WHERE id = ?");
        return $stmt->execute([$nome, $preco, $variacoes, $id]);
    }

    public function deletar($id) {
        $stmt = $this->pdo->prepare("DELETE FROM produtos WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function obterPorId($id) {
        $stmt = $this->pdo->prepare("SELECT p.*, e.quantidade FROM produtos p JOIN estoque e ON p.id = e.produto_id WHERE p.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
