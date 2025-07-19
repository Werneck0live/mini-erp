<?php

require_once 'models/Produto.php';

class ProdutoController {

    public function salvar($id) {
        $nome = $_POST['nome'];
        $preco = $_POST['preco'];
        $variacoes = $_POST['variacoes'];
        $produtoModel = new Produto();
        $produtoModel->criar($nome, $preco, $variacoes);

        // require 'views/produtos/cadastro.php';
        // header("Location: ../views/produtos/lista.php");
        $this->listar();
    }

    public function carregarProduto($id) {
        // Instancia o modelo Produto e busca o produto pelo ID
        $produtoModel = new Produto();
        $produto = $produtoModel->obterPorId($id);

        if(!empty($produto)){
            require 'views/produtos/editar.php';
        } else {
            $this->listar();
        }
    }

    public function atualizar($id) {
        $nome = $_POST['nome'];
        $preco = $_POST['preco'];
        $variacoes = $_POST['variacoes'];
        // $estoque = $_POST['estoque'];

        $produtoModel = new Produto();
        // $produtoModel->atualizar($id, $nome, $preco, $variacoes, $estoque);
        $produtoModel->atualizar($id, $nome, $preco, $variacoes);
        
        $this->listar();
        // header('Location: /produtos/lista');
    }

    public function listar() {
        $produtoModel = new Produto();
        $produtos = $produtoModel->listar();

        require 'views/produtos/lista.php';
    }


}
