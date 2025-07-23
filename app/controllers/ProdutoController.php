<?php

require_once '../models/Produto.php';
require_once '../models/Estoque.php';
require_once '../config/ErrorHandler.php';

class ProdutoController {

    private const ERROR_CADASTRAR_PRODUTO = "Erro ao cadastar o produto";

    public function salvar() {
        
        $nome = $_POST['nome'];
        $preco = $_POST['preco'];
        $variacoes = $_POST['variacoes'] ?? [];

        if (empty($variacoes)) {
            ErrorHandler::handleError(self::ERROR_CADASTRAR_PRODUTO, "../../produto/listarTodos");
            return;
        }

        try {
            $produtoModel = new Produto();
            $produtoModel->beginTransaction();

            // Produto principal (pai) — sem estoque nem preço
            $produtoPaiId = $produtoModel->criar($nome, $preco);
            if (!$produtoPaiId) {
                throw new Exception(self::ERROR_CADASTRAR_PRODUTO);
            }

            $estoqueModel = new Estoque();

            foreach ($variacoes as $v) {
                $descricao = trim($v['descricao']);
                $estoque = intval($v['estoque'] ?? 0);


                // Criar produto-filho (variação)
                // die(var_dump([
                //     'produtoPaiId' => $produtoPaiId,
                //     'nome' => $nome,
                //     'descricao' => $descricao,
                // ]));
                $idVariação = $produtoModel->criarvariacao($produtoPaiId, $nome, $descricao);
                if (!$idVariação || !$estoqueModel->adicionarEstoque($idVariação, $estoque)) {
                    throw new Exception(self::ERROR_CADASTRAR_PRODUTO);
                }
            }

            $produtoModel->commit();

            header("Location: ../produto/listarTodos");
            exit;
        } catch (Exception $e) {
            if (isset($produtoModel)) {
                $produtoModel->rollBack();
            }

            ErrorHandler::handleError(self::ERROR_CADASTRAR_PRODUTO, "../../produto/listarTodos");
        }
    }

    public function editar($id) {
        $produtoModel = new Produto();
        $grupoProduto = $produtoModel->obterGrupoPorParentId($id);

        if(!empty($grupoProduto)){
            $produto = [];

            foreach ($grupoProduto as $p) {
                if(is_null($p['id_produto_pai'])){
                    $produto['id'] = $p['id'];
                    $produto['nome'] = $p['nome'];
                    $produto['preco'] = $p['preco'];
                }else {
                    $tempArray['id'] = $p['id'];
                    $tempArray['descricao'] = $p['descricao'];
                    $tempArray['quantidade'] = $p['quantidade'];
                    $produto['variacoes'][] = $tempArray;
                }
            }

            require '../views/produtos/editar.php';
        } else {
            ErrorHandler::handleError("Erro ao carregar o produto", "../../produto/listarTodos");
        }
    }

    public function atualizar($id) {
        $nome = $_POST['nome'];
        $preco = $_POST['preco'];
        $variacoesInput = $_POST['variacoes'] ?? [];

        try {
            $produtoModel = new Produto();
            $produtoModel->beginTransaction();

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // Atualiza produto pai
            if (!$produtoModel->atualizar($id, $nome, $preco)) {
                throw new Exception("Erro ao atualizar o produto principal.");
            }

            // Variações antigas salvas no banco
            $variacoesAntigas = $produtoModel->listarVariacoes($id); // retorna array com 'id'

            // Mapeia as IDs das antigas
            $idsAntigos = array_column($variacoesAntigas, 'id');

            $idsAtuais = [];

            $estoqueModel = new Estoque();

            foreach ($variacoesInput as $variacao) {
                if (isset($variacao['id'])) {
                    // Atualizar variação existente
                    $variacaoId = $variacao['id'];
                    $idsAtuais[] = $variacaoId;

                    
                    $produtoModel->atualizarVariacao($variacaoId, $nome, $variacao['descricao']);
                    
                    if(!$estoqueModel->atualizarEstoque($variacaoId, $variacao['estoque'])) {
                        throw new Exception(self::ERROR_CADASTRAR_PRODUTO);
                    }
                
                    // Antes de remover, verificar se o produto está na sessão(checkout) e atualiza-lo
                    if(isset($_SESSION['carrinho'][$variacaoId])){
                        $quantidadePedido = $_SESSION['carrinho'][$variacaoId]['quantidade'];
                        
                        unset($_SESSION['carrinho'][$variacaoId]);

                        $arrayProduto = [
                            "produto_id"=> $variacaoId,
                            "nome"=> $nome,
                            "descricao"=> $variacao['descricao'],
                            "quantidade"=> $quantidadePedido,
                            "preco_unitario"=> $preco,
                            "estoque"=>  $variacao['estoque'],
                        ];

                        $_SESSION['carrinho'][$variacaoId] = $arrayProduto;
                    }
                    
                } else {
                    // Criar produto-filho (variação)
                    $idVariação = $produtoModel->criarvariacao($id, $nome, $variacao['descricao']);
                    
                    if (!$idVariação || !$estoqueModel->adicionarEstoque($idVariação, $variacao['estoque'])) {
                        throw new Exception(self::ERROR_CADASTRAR_PRODUTO);
                    }
                }
            }

            // Verifica variações removidas (estavam no banco, mas não vieram no POST)
            $idsParaRemover = array_diff($idsAntigos, $idsAtuais);

            foreach ($idsParaRemover as $removerId) {
                
                // Antes de remover, verificar se o produto está na sessão(checkout) e remove-lo
                if(isset($_SESSION['carrinho'][$removerId])){
                    unset($_SESSION['carrinho'][$removerId]);
                }
                
                if (!$produtoModel->deletar($removerId) || !$estoqueModel->deletar($removerId)) {
                    throw new Exception(self::ERROR_CADASTRAR_PRODUTO);
                }
            }

            $produtoModel->commit();
            header("Location: /produto/listarTodos");
            exit;
        } catch (Exception $e) {
            if (isset($produtoModel)) {
                $produtoModel->rollBack();
            }

            ErrorHandler::handleError("Erro ao atualizar produto e variações: " . $e->getMessage(), "/produto/listarTodos");
        }
    }

    public function deletar($id) {
        $produtoModel = new Produto();
        $produtoModel->beginTransaction();
        
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // Antes de remover, verificar se o produto está na sessão(checkout) e remove-lo
            $grupoProduto = $produtoModel->obterGrupoPorParentId($id);

            foreach ($grupoProduto as $produto) {
                if(isset($_SESSION['carrinho'][$produto['id']])){
                    unset($_SESSION['carrinho'][$produto['id']]);
                }                
            }

            if ($produtoModel->deletarGrupo($id)) {
                
                $produtoModel->commit();

                header("Location: ../listarTodos");
                exit();
            } else {
                ErrorHandler::handleError("Falha ao deletar o produto", "../../produto/listarTodos");
            }
        } catch (Exception $e) {

            if (isset($produtoModel)) {
                $produtoModel->rollBack();
            }

            ErrorHandler::handleError("Falha ao deletar o produto", "../../pedido/checkout");
        }
    }

    public function listarTodos() {
        $produtoModel = new Produto();
        $produtos = $produtoModel->listarTodos();

        require '../views/produtos/listar.php';
    }

    public function comprar($id) {
        $produtoModel = new Produto();
        $grupoProduto = $produtoModel->obterGrupoPorParentId($id);

        if(!empty($grupoProduto)){
            $produto = [];

            foreach ($grupoProduto as $p) {
                if(is_null($p['id_produto_pai'])){
                    $produto['id'] = $p['id'];
                    $produto['nome'] = $p['nome'];
                    $produto['preco'] = $p['preco'];
                }else {
                    $tempArray['id'] = $p['id'];
                    $tempArray['descricao'] = $p['descricao'];
                    $tempArray['quantidade'] = $p['quantidade'];
                    $produto['variacoes'][] = $tempArray;
                }
            }
            
            require '../views/produtos/comprar.php';
        } else {
            ErrorHandler::handleError("Erro ao carregar dados do produto", "../../produto/listarTodos");
        }
    }
}
