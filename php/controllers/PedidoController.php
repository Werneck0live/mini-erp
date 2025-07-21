<?php
require_once 'models/Pedido.php';
require_once 'models/Produto.php';
require_once 'models/Cupom.php';
require_once 'config/bd.php';

if (session_status() !== PHP_SESSION_ACTIVE && !headers_sent()) {
    session_start();
}

class PedidoController {

    public function adicionar($produtoId) {

        $produto_id = $produtoId;
        $quantidade = $_POST['quantidade'];
        
        $produto = new Produto();
        $produto_info = $produto->obterPorId($produto_id);
        
        if($quantidade > $produto_info['quantidade']){
            //TODO: tratamento de erro
            // die(var_dump([
            //     'quantidade-pedido'=> $quantidade,
            //     'estoque'=>$produto_info['quantidade']
            // ]));
            // die('deu ruim');
        } else {

            $item = [
                'produto_id' => $produto_id,
                'nome' => $produto_info['nome'],
                'quantidade' => $quantidade,
                'preco_unitario' => $produto_info['preco'],
                'estoque' => $produto_info['quantidade']
            ];
            
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            $_SESSION['carrinho'][] = $item;
        }

        header("Location: ../../produto/listar");
    }

    public function finalizar($id){
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
        $pedido = new Pedido();
        $pedido_id = $pedido->criar($subtotal, $frete, $total, $cep);

        // Adicionar os itens ao pedido
        foreach ($_SESSION['carrinho'] as $item) {
            $pedido->adicionarItem($pedido_id, $item['produto_id'], $item['quantidade'], $item['preco_unitario']);
        }
        
        // Limpar o carrinho após finalizar
        unset($_SESSION['carrinho']);
        
        header("Location: ../views/pedidos/resumo.php?id=$pedido_id");
    }
    
}
