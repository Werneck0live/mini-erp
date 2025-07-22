<?php
require_once 'config/constants.php';

function calcular_frete($subtotal)
{
    if ($subtotal >= VALOR_MAXIMO_PEDIDO_PARA_FRETE) { // > 200
        return VALOR_MINIMO_RESULTADO_FRETE; // 0
    } elseif ($subtotal >= VALOR_MINIMO_PEDIDO_PARA_FRETE && $subtotal <= VALOR_MEDIO_PEDIDO_PARA_FRETE) { // >= 52.00 <= 166.59
        return VALOR_MEDIO_RESULTADO_FRETE; // 15.00
    } else {
        return VALOR_MAXIMO_RESULTADO_FRETE; // 20.00
    }
}
?>