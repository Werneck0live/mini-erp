<?php

require_once '../models/BaseModel.php';

class Pedido  extends BaseModel{

    const TABLE_PRODUTO = 'produtos';

    public function criar($subtotal, $frete, $desconto, $total, $status, $cepCliente, $enderecoCliente, $emailCliente) {
        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (subtotal, frete, valor_desconto, total, `status`, cep, endereco, email_cliente) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        if($stmt->execute([$subtotal, $frete, $desconto, $total, $status, $cepCliente, $enderecoCliente, $emailCliente])){
            $ultimoId = $this->pdo->lastInsertId();
            return $ultimoId;            
        } else {
            return 0;
        }
    }

    public function atualizarStatus($id, $status)
    {
        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    public function contarTotal()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM {$this->table}");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    public function listarPaginado($offset, $limite)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE `status` <> 'inativo' ORDER BY id DESC LIMIT :offset, :limite");
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}
