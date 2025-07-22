<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini ERP</title>
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
</head>
<body class="container mt-5">
    <header>
        <div class="mb-4">
            <a href="../../produto/cadastrar" class="btn btn-link">Cadastrar Produto</a>
            <a href="../../produto/listarTodos" class="btn btn-link">Produtos</a>
            <a href="../../pedido/listar" class="btn btn-link">Pedidos</a>
            <a href="../../cupom/listarTodos" class="btn btn-link">Cupons</a>
            <a href="../../pedido/checkout" class="btn btn-link">Finalizar Compra</a>
        </div>
        <?php if (!empty($_SESSION['mensagem_erro'])): ?>
        <div id="mensagem-alerta" class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_SESSION['mensagem_erro']) ?>
                <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="alert" onclick="document.getElementById('mensagem-alerta').remove();">x</button>
            </div>
            <?php unset($_SESSION['mensagem_erro']); ?>
        </div>
        <?php endif; ?>
    </header>
    <hr>
