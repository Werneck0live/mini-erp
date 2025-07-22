<?php

require_once 'models/BaseModel.php';

class Cupom extends BaseModel{
    
    public function buscarPorCodigo($codigo, $subtotal) {
        $stmt = $this->pdo->prepare("SELECT * FROM cupons WHERE codigo = ? AND validade >= CURDATE()");
        $stmt->execute([$codigo]);
        $cupom = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cupom && $subtotal >= $cupom['valor_minimo']) {
            return $cupom['desconto'];
        }

        return 0;
    }

}
