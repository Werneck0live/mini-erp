<?php

require_once 'models/BaseModel.php';

class Cupom extends BaseModel{
    
    public function buscarPorCodigo($codigo, $subtotal) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE codigo = ? AND validade >= CURDATE()");
        $stmt->execute([$codigo]);
        $cupom = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cupom && $subtotal >= $cupom['valor_minimo']) {
            return $cupom['percentual'];
        }

        return 0;
    }

    public function salvar($codigo,$valor_minimo,$percentualDesconto,$validade){
        
        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (codigo, valor_minimo, percentual, validade) VALUES (?, ?, ?, ?)");
        return $stmt->execute([
                        $codigo,
                        $valor_minimo,
                        $percentualDesconto,
                        $validade
                    ]);
    }

}
