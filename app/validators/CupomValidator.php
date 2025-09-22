<?php

class CupomValidator {
    
    public static function validarSalvar($codigo, $valor_minimo, $percentual_desconto, $validade) {
        
        if (empty($codigo) || !is_string($codigo)) {
            throw new Exception("Código do cupom inválido.");
        }

        if (empty($valor_minimo) || !is_numeric($valor_minimo) || $valor_minimo <= 0) {
            throw new Exception("Valor mínimo inválido.");
        }

        if (empty($percentual_desconto) || !is_numeric($percentual_desconto) || $percentual_desconto < 0 || $percentual_desconto > 100) {
            throw new Exception("Percentual de desconto inválido.");
        }
        
        // Validar validade (formato da data)
        if (empty($validade) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $validade)) {
            throw new Exception("Data de validade inválida.");
        }
    }

    public static function validarValidar($codigo, $subtotal) {
        
        if (empty($codigo)) {
            throw new Exception("Código do cupom não pode ser vazio.");
        }

        if ($subtotal <= 0) {
            throw new Exception("Subtotal inválido. Deve ser maior que zero.");
        }
    }
}
?>
