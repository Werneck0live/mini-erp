<?php

require_once 'models/Produto.php';
require_once 'models/Estoque.php';
require_once 'config/ErrorHandler.php';

class ProdutoController {

    public function salvar() {
        
        $nome = $_POST['nome'];
        $preco = $_POST['preco'];
        $variacoes = $_POST['variacoes'];
        $quantidade = $_POST['estoque'];
               
        try {
            
            $produtoModel = new Produto();
            $produtoModel->beginTransaction();
            $produtoId = $produtoModel->criar($nome, $preco, $variacoes);

            if ($produtoId > 0) {
                
                $estoqueModel = new Estoque();
                if (!$estoqueModel->adicionarEstoque($produtoId, $quantidade)) {
                    throw new Exception("Erro ao atualizar estoque.");
                }

                $produtoModel->commit();
                header("Location: ../listar");
                exit;
            } else {
                ErrorHandler::handleError("Falha ao cadastrar produto", "../../produto/listarTodos");
            }
        } catch (Exception $e) {
            if (isset($produtoModel)) {
                $produtoModel->rollBack(); // Desfaz todas as alterações feitas na transação
            }

            ErrorHandler::handleError("Falha ao cadastrar produto", "../../produto/listarTodos");
        }
    }

    public function editar($id) {
        
        $produtoModel = new Produto();
        $produto = $produtoModel->obterPorId($id);

        if(!empty($produto)){
            require 'views/produtos/editar.php';
        } else {
            ErrorHandler::handleError("Erro ao carregar o produto", "../../produto/listarTodos");
        }
    }

    public function atualizar($id) {
        $nome = $_POST['nome'];
        $preco = $_POST['preco'];
        $variacoes = $_POST['variacoes'];
        $quantidade = $_POST['estoque'];

        try {
            
            $produtoModel = new Produto();
            $produtoModel->beginTransaction();

            // Atualiza o produto
            if ($produtoModel->atualizar($id, $nome, $preco, $variacoes)) {
                // Se a atualização do produto for bem-sucedida, tenta atualizar o estoque
                $estoqueModel = new Estoque();
                if (!$estoqueModel->atualizarEstoque($id, $quantidade)) {
                    throw new Exception("Erro ao atualizar o estoque.");
                }

                $produtoModel->commit();
                header("Location: ../listar");
                exit;
            } else {
                ErrorHandler::handleError("Falha ao atualizar o produto", "../../produto/listarTodos");
            }
        } catch (Exception $e) {
            if (isset($produtoModel)) {
                $produtoModel->rollBack(); // Desfaz todas as alterações feitas na transação
            }

            ErrorHandler::handleError("Falha ao atualizar o produto", "../../produto/listarTodos");
        }
    }

    public function deletar($id) {

        $produtoModel = new Produto();
        
        if ($produtoModel->deletar($id)) {
            
            header("Location: ../listar");
            exit;
        } else {
            ErrorHandler::handleError("Falha ao deletar o produto", "../../produto/listarTodos");
        }
    }

    public function listarTodos() {
        $produtoModel = new Produto();
        $produtos = $produtoModel->listarTodosNaoInativos();

        if(empty($produtos)){
            ErrorHandler::handleError("Nenhum produto ativo cadastrado", "../../produto/listarTodos");
        }

        require 'views/produtos/listar.php';
    }

    public function comprar($id) {
        $produtoModel = new Produto();
        $produto = $produtoModel->obterPorId($id);

        if(!empty($produto)){
            require 'views/produtos/comprar.php';
        } else {
            ErrorHandler::handleError("Erro ao carregar dados do produto", "../../produto/listarTodos");
        }
    }
}
