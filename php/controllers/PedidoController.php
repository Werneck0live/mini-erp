<?php
require_once '../models/Pedido.php';
require_once '../models/Produto.php';
require_once '../models/Cupom.php';
require_once '../db.php';

session_start();

// Adicionar item ao carrinho
if (isset($_POST['adicionar_item'])) {
    $produto_id = $_POST['produto_id'];
    $quantidade = $_POST['quantidade'];
    
    // Recuperar informações do produto
    $produto = new Produto($pdo);
    $produto_info = $produto->obterPorId($produto_id);
    
    // Verificar se o produto já está no carrinho
    $item = [
        'produto_id' => $produto_id,
        'nome' => $produto_info['nome'],
        'quantidade' => $quantidade,
        'preco_unitario' => $produto_info['preco']
    ];
    
    // Adicionar ao carrinho
    $_SESSION['carrinho'][] = $item;

    header("Location: ../views/pedidos/carrinho.php");
}

// Finalizar pedido
if (isset($_POST['finalizar_pedido'])) {
    $cep = $_POST['cep'];
    
    // Calcular o subtotal
    $subtotal = 0;
    foreach ($_SESSION['carrinho'] as $item) {
        $subtotal += $item['quantidade'] * $item['preco_unitario'];
    }
    
    // Calcular o frete
    if ($subtotal >= 200.00) {
        $frete = 0;
    } elseif ($subtotal >= 52.00 && $subtotal <= 166.59) {
        $frete = 15.00;
    } else {
        $frete = 20.00;
    }
    
    $total = $subtotal + $frete;
    
    // Criar o pedido
    $pedido = new Pedido($pdo);
    $pedido_id = $pedido->criar($subtotal, $frete, $total, $cep);

    // Adicionar os itens ao pedido
    foreach ($_SESSION['carrinho'] as $item) {
        $pedido->adicionarItem($pedido_id, $item['produto_id'], $item['quantidade'], $item['preco_unitario']);
    }
    
    // Limpar o carrinho após finalizar
    unset($_SESSION['carrinho']);
    
    header("Location: ../views/pedidos/resumo.php?id=$pedido_id");
}
