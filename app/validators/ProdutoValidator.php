<?php

class ProdutoValidator {

    public static function validarSalvar($nome, $preco, $variacoes) {
        
        if (empty($nome)) {
            throw new Exception("Nome do produto não pode ser vazio.");
        }

        if (!is_numeric($preco) || $preco <= 0) {
            throw new Exception("Preço do produto inválido.");
        }

        if (empty($variacoes)) {
            throw new Exception("É necessário cadastrar ao menos uma variação para o produto.");
        }

        foreach ($variacoes as $v) {
            if (empty($v['descricao'])) {
                throw new Exception("Descrição da variação não pode ser vazia.");
            }

            if (isset($v['estoque']) && (!is_numeric($v['estoque']) || $v['estoque'] < 0)) {
                throw new Exception("Estoque da variação deve ser um número válido e não negativo.");
            }
        }
    }

    public static function validarEditar($nome, $preco, $variacoes) {
        
        if (empty($nome)) {
            throw new Exception("Nome do produto não pode ser vazio.");
        }

        if (!is_numeric($preco) || $preco <= 0) {
            throw new Exception("Preço do produto inválido.");
        }

        foreach ($variacoes as $v) {
            if (empty($v['descricao'])) {
                throw new Exception("Descrição da variação não pode ser vazia.");
            }

            if (isset($v['estoque']) && (!is_numeric($v['estoque']) || $v['estoque'] < 0)) {
                throw new Exception("Estoque da variação deve ser um número válido e não negativo.");
            }
        }
    }

    public static function validarAtualizar($nome, $preco, $variacoesInput) {
        
        if (empty($nome)) {
            throw new Exception("Nome do produto não pode ser vazio.");
        }

        if (!is_numeric($preco) || $preco <= 0) {
            throw new Exception("Preço do produto inválido.");
        }

        foreach ($variacoesInput as $variacao) {
            if (isset($variacao['descricao']) && empty($variacao['descricao'])) {
                throw new Exception("Descrição da variação não pode ser vazia.");
            }

            if (isset($variacao['estoque']) && (!is_numeric($variacao['estoque']) || $variacao['estoque'] < 0)) {
                throw new Exception("Estoque da variação deve ser um número válido e não negativo.");
            }
        }
    }

    public static function validarDeletar($id) {
        
        if (empty($id) || !is_numeric($id)) {
            throw new Exception("ID do produto inválido.");
        }
    }

    public static function validarComprar($id, $grupoProduto) {
        
        if (empty($id) || !is_numeric($id)) {
            throw new Exception("ID do produto inválido.");
        }

        if (empty($grupoProduto)) {
            throw new Exception("Grupo de produtos não encontrado.");
        }
    }
}
?>
