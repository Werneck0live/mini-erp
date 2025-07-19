<?php
class Cupom {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function aplicarCupom($codigo, $subtotal) {
        $stmt = $this->pdo->prepare("SELECT * FROM cupons WHERE codigo = ? AND validade >= CURDATE()");
        $stmt->execute([$codigo]);
        $cupom = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cupom && $subtotal >= $cupom['valor_minimo']) {
            return $cupom['desconto'];
        }

        return 0;
    }

    public function listarCupons() {
        $stmt = $this->pdo->query("SELECT * FROM cupons");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
