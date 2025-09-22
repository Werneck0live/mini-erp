<?php
require_once '../models/Pedido.php';
require_once '../validators/PedidoValidator.php';
require_once '../models/Produto.php';
require_once '../models/Estoque.php';
require_once '../models/Cupom.php';
require_once '../config/constants.php';
require_once '../controllers/MailController.php';
require_once '../config/helpers/frete.php';
require_once '../config/ErrorHandler.php';

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

    public function adicionar() {

        if(!empty($_POST['variacoes'])){
            try {
                
                foreach ($_POST['variacoes'] as $variacao) {
                
                    if(empty($variacao['quantidade'])){
                        continue;
                    }

                    PedidoValidator::validarAdicionar($variacao);
                
                    $produtoId = $variacao['id'];
                    $quantidade = $variacao['quantidade'];
                    
                    $produto = new Produto();
                    $produtoInfo = $produto->obterDadosVariacaoPorId($produtoId);

                    if (empty($produtoInfo)) {
                        ErrorHandler::handleError("Produto não encontrado", "../../produto/listarTodos");
                    }
                    
                    if($quantidade > $produtoInfo['quantidade']){
                        ErrorHandler::handleError("Saldo em estoque insuficiente", "../../produto/listarTodos");
                    } else {

                        if (!isset($_SESSION['carrinho'][$produtoId])) {
                            $item = [
                                'produto_id' => $produtoId,
                                'nome' => $produtoInfo['nome'],
                                'descricao' => $produtoInfo['descricao'],
                                'quantidade' => $quantidade,
                                'preco_unitario' => $produtoInfo['preco'],
                                'estoque' => $produtoInfo['quantidade']
                            ];
                            
                            $_SESSION['carrinho'][$produtoId] = $item;
                        } else {
                            $_SESSION['carrinho'][$produtoId]['quantidade'] += (int)$quantidade;
                        }
                    }
                }
                
                header("Location: ../../produto/listarTodos");
                exit();
            } catch (Exception $e) {
                ErrorHandler::handleError($e->getMessage(), "../../produto/listarTodos");
            }
        } else {
            ErrorHandler::handleError('Erro ao carregar dados do produto', "../../produto/listarTodos");
        }
    }

    public function removerItem($produtoId) {
        
        if (!isset($_SESSION['carrinho'])) {
            ErrorHandler::handleError("Erro ao carregar dados do carrinho", "/checkout");
            exit;
        }

        try {
            PedidoValidator::validarRemoverItem($produtoId, $_SESSION['carrinho']);

            $_SESSION['carrinho'] = array_filter($_SESSION['carrinho'], function ($item) use ($produtoId) {
                return $item['produto_id'] != $produtoId;
            });

            header("Location: ../../pedido/checkout");
            exit();
        } catch (Exception $e) {
            ErrorHandler::handleError($e->getMessage(), "../../pedido/checkout");
        }
    }
    
    public function finalizar(){
        try {
        
            PedidoValidator::validarFinalizar($_SESSION['carrinho'], $_POST['cep'], $_POST['endereco'], $_POST['email'], $_POST['quantidade']);
            
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
            
            // helper calculo de frete
            $frete = calcular_frete($subtotal);

            $desconto = 0;    
            
            
            if (!empty($_POST['codigo_cupom'])) {
                $cupom = new Cupom();
                $percentualCupom = $cupom->buscarPorCodigo($_POST['codigo_cupom'], $subtotal);
            
                if(!empty($percentualCupom)){
                    $percentualCupom = $percentualCupom['percentual'];
                    
                    if ($percentualCupom > 0) {
                        $desconto = $subtotal * ($percentualCupom / 100);
                    }
                }
            }

            $total = ($subtotal + $frete) - $desconto;

            $pedidoModel = new Pedido();
            $pedidoModel->beginTransaction();
            try {
                
                // Criar o pedido
                $pedidoId = $pedidoModel->criar(
                    $subtotal,
                    $frete,
                    $desconto,
                    $total,
                    $status,
                    $cepCliente,
                    $enderecoCliente,
                    $emailCliente
                );

                if((bool)!($pedidoId)){
                    throw new Exception();
                } else {
                    $descricaoItensEmail = '';
                    // Adicionar os itens ao pedido
                    foreach ($carrinho as $item) {
                            
                        $produtoEstoque = new Estoque();
                        $qtdEstoque = $item['estoque'] - $item['quantidade'];

                        $produtoEstoque->atualizarEstoque($item['produto_id'], $qtdEstoque);
                        
                        $descricaoItensEmail .= "\nitem: ".$item['nome']." ".$item['descricao']." - quantidade: " . $item['quantidade']
                                                ." - valor unitário: ".$item['preco_unitario'] 
                                                . " - subtotal: " .number_format($item['quantidade'] * $item['preco_unitario'], 2, ',', '.')
                                                . "\n";
                    }

                    $pedidoModel->commit();

                    $textoDesconto = "";
                    if($desconto > 0 ){
                        $textoDesconto = "\nDesconto: " . number_format($desconto, 2, ',', '.') . " (".$percentualCupom."%)";
                    }

                    $tituloEmail = NOME_PROJETO . " - Resumo - Pedido de número $pedidoId";
                    $corpoEmail  = "Olá, \n\nSegue as informações do seu pedido de número $pedidoId:\n"
                                . "\n".DIVISOR_TEXTO_EMAIL
                                . $descricaoItensEmail
                                . DIVISOR_TEXTO_EMAIL."\n\n"
                                ."\nSubTotal: " . number_format($subtotal, 2, ',', '.')
                                ."\nFrete: " . number_format($frete, 2, ',', '.')
                                . $textoDesconto
                                ."\n\nTotal: R$" . number_format($total, 2, ',', '.');

                    try {
                        $mail = new MailController();
                        $mail->sendMail($emailCliente, $tituloEmail, $corpoEmail);
                    } catch (Exception $e) {
                        $mensagem = "Pedido criado. Houve problema ao enviar"
                                        ." e-mail do pedido para o usuário: ".$e->getMessage()
                                        .". Revise as configurações do seu servidor SMTP";

                        ErrorHandler::handleError($mensagem, "../../produto/listarTodos");
                    }
                    
                    // Limpando a sessão
                    unset($_SESSION['carrinho']);
                    
                    header("Location: ../../produto/listarTodos");
                    exit();
                }
            } catch (Exception $e) {
                if (isset($pedidoModel)) {
                    $pedidoModel->rollBack();
                }

                ErrorHandler::handleError("Erro ao finalizar pedido", "../../pedido/checkout");
            }
        } catch (Exception $e) {
            ErrorHandler::handleError($e->getMessage(), "../../pedido/checkout");
        }

    }
    
    public function listar() {
        $pagina = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
        $limite = LIMITE_PAGINACAO_LISTA_PEDIDOS;
        $offset = ($pagina - 1) * $limite;

        $pedido = new Pedido();
        $total = $pedido->contarTotal();
        $totalPaginas = ceil($total / $limite);

        $pedidos = $pedido->listarPaginado($offset, $limite);

        include __DIR__ . '/../views/pedidos/relatorio.php';
    }

}
