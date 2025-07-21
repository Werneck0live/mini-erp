<?php

require_once 'config/bd.php';

class Pedido {
    private $pdo;

    public function __construct() {        
        $pdo = DB::getConnection();
        $this->pdo = $pdo;
    }

    public function criar($subtotal, $frete, $total, $cep) {
        $stmt = $this->pdo->prepare("INSERT INTO pedidos (subtotal, frete, total, cep, status) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$subtotal, $frete, $total, $cep, 'Pendente']);
    }

    public function adicionarItem($pedido_id, $produto_id, $quantidade, $preco_unitario) {
        $stmt = $this->pdo->prepare("INSERT INTO itens_pedido (pedido_id, produto_id, quantidade, preco_unitario) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$pedido_id, $produto_id, $quantidade, $preco_unitario]);
    }
}
