<?php

class PedidoValidator {

    public static function validarAdicionar($variacao) {
        if (empty($variacao['quantidade']) || !is_numeric($variacao['quantidade']) || $variacao['quantidade'] <= 0) {
            throw new Exception("Quantidade inválida para o produto.");
        }
    }

    public static function validarRemoverItem($produtoId, $carrinho) {
        if (!isset($produtoId) || !isset($carrinho[$produtoId])) {
            throw new Exception("Produto não encontrado no carrinho.");
        }
    }

    public static function validarFinalizar($carrinho, $cepCliente, $enderecoCliente, $emailCliente, $quantidades) {
        if (empty($carrinho) || !is_array($carrinho)) {
            throw new Exception("Carrinho está vazio ou corrompido.");
        }

        // Validar CEP
        if (empty($cepCliente) || !preg_match('/^\d{5}-\d{3}$/', $cepCliente)) {
            throw new Exception("CEP inválido.");
        }

        if (empty($enderecoCliente)) {
            throw new Exception("Endereço não pode ser vazio.");
        }

        if (empty($emailCliente) || !filter_var($emailCliente, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email inválido.");
        }

        foreach ($quantidades as $produtoId => $novaQtd) {            
            if (!isset($carrinho[$produtoId])) {
                throw new Exception("Produto não encontrado no carrinho para atualização de quantidade.");
            }

            if ($novaQtd <= 0) {
                throw new Exception("Quantidade inválida para o produto $produtoId.");
            }
        }
    }
}
?>
