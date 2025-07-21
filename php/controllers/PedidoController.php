<?php
require_once 'models/Pedido.php';
require_once 'models/Produto.php';
require_once 'models/Estoque.php';
require_once 'models/Cupom.php';
require_once 'config/bd.php';
require_once 'config/constants.php';
require_once 'controllers/MailController.php';


class PedidoController {

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }
    }

    public function adicionar($produtoId) {

        $produto_id = $produtoId;
        $quantidade = $_POST['quantidade'];
        
        $produto = new Produto();
        $produto_info = $produto->obterPorId($produto_id);


        if (!$produto) {
             // return ['erro' => 'Produto não encontrado.'];
            header("Location: ../../produto/listar");
        }
        
        if($quantidade > $produto_info['quantidade']){
            //TODO: tratamento de erro
            // return ['erro' => 'Produto não encontrado.'];
            header("Location: ../../produto/listar");
        } else {

            if (!isset($_SESSION['carrinho'][$produtoId])) {
                $item = [
                    'produto_id' => $produto_id,
                    'nome' => $produto_info['nome'],
                    'quantidade' => $quantidade,
                    'preco_unitario' => $produto_info['preco'],
                    'estoque' => $produto_info['quantidade']
                ];
                
                $_SESSION['carrinho'][$produto_id] = $item;
            } else {
                $_SESSION['carrinho'][$produtoId]['quantidade'] += (int)$quantidade;
            }
        }

        header("Location: ../../produto/listar");
    }

    public function removerItem($produtoId) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['carrinho'])) {
            header("Location: /checkout");
            exit;
        }

        $_SESSION['carrinho'] = array_filter($_SESSION['carrinho'], function ($item) use ($produtoId) {
            return $item['produto_id'] != $produtoId;
        });

        header("Location: ../../pedido/checkout");
    }
    
    public function finalizar(){
        
        if (isset($_POST['quantidade']) && is_array($_POST['quantidade'])) {
            $carrinho = [];
            foreach ($_POST['quantidade'] as $produtoId => $novaQtd) {
                foreach ($_SESSION['carrinho'] as $key => &$item) {
                    if ($item['produto_id'] == $produtoId) {
                        $item['quantidade'] = (int) $novaQtd;
                        $carrinho[] = $item;
                    }
                }
            }

        } else {
            if(empty($_SESSION['carrinho'])){
                header("Location: ../../pedido/checkout");
                exit();
            }

            $carrinho = $_SESSION['carrinho'];
        }

        $cepCliente = $_POST['cep'];
        $enderecoCliente = $_POST['endereco'];
        $emailCliente = $_POST['email'];
        $status = 'pendente';
        
        // Calcular o subtotal
        $subtotal = 0;
        foreach ($carrinho as $item) {
            $subtotal += $item['quantidade'] * $item['preco_unitario'];
        }
        
        // Calcular o frete
        if ($subtotal >= VALOR_MAXIMO_PEDIDO_PARA_FRETE) {
            $frete = 0;
        } elseif ($subtotal >= VALOR_MINIMO_PEDIDO_PARA_FRETE && $subtotal <= VALOR_MEDIO_PEDIDO_PARA_FRETE) {
            $frete = 15.00;
        } else {
            $frete = 20.00;
        }
        
        $total = $subtotal + $frete;
        
        // Criar o pedido
        $pedido = new Pedido();
        $pedidoId = $pedido->criar(
            $subtotal,
            $frete,
            $total,
            $status,
            $cepCliente,
            $enderecoCliente,
            $emailCliente
        );


        if((bool)!($pedidoId)){
            unset($_SESSION['carrinho']);
            header("Location: ../views/pedidos/resumo.php?id=$pedidoId");
        } else {
            // Adicionar os itens ao pedido
            $descricaoItensEmail = '';
            foreach ($carrinho as $item) {
                $pedido->adicionarItem($pedidoId, $item['produto_id'], $item['quantidade'], $item['preco_unitario']);
                $produtoEstoque = new Estoque();
                $qtdEstoque = $item['estoque'] - $item['quantidade'];
                $produtoEstoque->atualizarEstoque($item['produto_id'], $qtdEstoque);
                
                $descricaoItensEmail .= "\nitem: ".$item['nome']." - quantidade: " . $item['quantidade']
                                        ." - valor unitário: ".$item['preco_unitario'] 
                                        . " - subtotal: " .number_format($item['quantidade'] * $item['preco_unitario'], 2, ',', '.')
                                        . "\n";
            }
            
            $tituloEmail = NOME_PROJETO . " - Resumo do Pedido número $pedidoId";
            $corpoEmail  = "Olá, \n\nSegue as informações do seu pedido número $pedidoId:\n"
                          . "\n".DIVISOR_TEXTO_EMAIL
                          . $descricaoItensEmail
                          . DIVISOR_TEXTO_EMAIL."\n\n"
                          ."\nTotal: R$" . number_format($total, 2, ',', '.');            

            $mail = new MailController();
            $mail->sendMail($emailCliente, $tituloEmail, $corpoEmail);
            
            // Limpando a sessão
            unset($_SESSION['carrinho']);
            
            header("Location: ../../produto/listar");
        }

    }
    
}
