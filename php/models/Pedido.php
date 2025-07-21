<?php

require_once 'config/bd.php';

class Pedido {
    private $pdo;

    public function __construct() {        
        $pdo = DB::getConnection();
        $this->pdo = $pdo;
    }

    public function criar($subtotal, $frete, $total, $status, $cepCliente, $enderecoCliente, $emailCliente) {
        $stmt = $this->pdo->prepare("INSERT INTO pedidos (subtotal, frete, total, `status`, cep, endereco, email_cliente) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if($stmt->execute([$subtotal, $frete, $total, $status, $cepCliente, $enderecoCliente, $emailCliente])){
            $ultimoId = $this->pdo->lastInsertId();
            return $ultimoId;            
        } else {
            return 0;
        }
    }

    public function adicionarItem($pedido_id, $produto_id, $quantidade, $preco_unitario) {
        $stmt = $this->pdo->prepare("INSERT INTO itens_pedido (pedido_id, produto_id, quantidade, preco_unitario) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$pedido_id, $produto_id, $quantidade, $preco_unitario]);
    }
}
