<?php

require_once 'models/Produto.php';
require_once 'models/Estoque.php';

class ProdutoController {

    public function salvar() {
        $nome = $_POST['nome'];
        $preco = $_POST['preco'];
        $variacoes = $_POST['variacoes'];
        $quantidade = $_POST['estoque'];
        $produtoModel = new Produto();
        $produtoId = $produtoModel->criar($nome, $preco, $variacoes);

        $estoqueModel = new Estoque();
        $estoqueModel->adicionarEstoque($produtoId, $quantidade);        
        
        header("Location: listar");
        exit;
    }

    public function editar($id) {
        
        $produtoModel = new Produto();
        $produto = $produtoModel->obterPorId($id);

        if(!empty($produto)){
            require 'views/produtos/editar.php';
        } else {
            header("Location: listar");
        }
    }

    public function atualizar($id) {
        
        $nome = $_POST['nome'];
        $preco = $_POST['preco'];
        $variacoes = $_POST['variacoes'];
        $quantidade = $_POST['estoque'];

        $produtoModel = new Produto();
        $produtoModel->atualizar($id, $nome, $preco, $variacoes);
        
        $estoqueModel = new Estoque();
        $estoqueModel->atualizarEstoque($id, $quantidade);
        
        header("Location: ../listar");
    }

    public function deletar($id) {

        $produtoModel = new Produto();
        $produtoModel->deletar($id);
        
        header("Location: ../listar");
    }

    public function listar() {
        $produtoModel = new Produto();
        $produtos = $produtoModel->listar();

        require 'views/produtos/listar.php';
    }

    public function comprar($id) {
        $produtoModel = new Produto();
        $produto = $produtoModel->obterPorId($id);

        if(!empty($produto)){
            require 'views/produtos/comprar.php';
        } else {
            header("Location: listar");
        }
    }
}
