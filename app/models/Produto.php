<?php

require_once '../models/BaseModel.php';

class Produto  extends BaseModel{

    public function criar($nome, $preco) {
        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (nome, preco) VALUES (?, ?)");
        if($stmt->execute([$nome, $preco])){
            $ultimoId = $this->pdo->lastInsertId();
            return $ultimoId;            
        } else {
            return 0;
        }
    }
    
    public function listar() {
        $stmt = $this->pdo->query("SELECT * FROM produtos");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function atualizar($id, $nome, $preco) {
        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET nome = ?, preco = ? WHERE id = ?");
        return $stmt->execute([$nome, $preco, $id]);
    }

    public function atualizarVariacao($id, $nome, $descricao) {
        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET nome = ?, descricao = ? WHERE id = ?");
        return $stmt->execute([$nome, $descricao, $id]);
    }

    public function obterPorId($id) {
        $stmt = $this->pdo->prepare("SELECT p.*, e.quantidade FROM {$this->table} p JOIN estoque e ON p.id = e.produto_id WHERE p.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obterDadosVariacaoPorId($id) {
        $stmt = $this->pdo->prepare("SELECT 
                                        p.id, p.nome, p.descricao, e.quantidade, (SELECT preco FROM produtos WHERE id = p.parent_id) as preco
	                                    FROM erp.produtos p 
                                        JOIN estoque e ON p.id = e.produto_id 
                                        WHERE p.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obterGrupoPorParentId($id) {
        $stmt = $this->pdo->prepare("SELECT p.*, e.quantidade FROM {$this->table} p LEFT JOIN estoque e ON p.id = e.produto_id WHERE (p.id = ? OR parent_id = ?) AND status = 'ativo'");
        $stmt->execute([$id, $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function listarVariacoes($parentId) {
        $stmt = $this->pdo->prepare("SELECT p.*, e.quantidade FROM {$this->table} p LEFT JOIN estoque e ON p.id = e.produto_id WHERE parent_id = ?");
        $stmt->execute([$parentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function criarvariacao($parentId, $nome, $descricao) {
        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (nome, descricao, parent_id) VALUES (?, ?, ?)");
        if ($stmt->execute([$nome, $descricao, $parentId])) {
            return $this->pdo->lastInsertId();
        } else {
            return 0;
        }
    }

    public function listarTodos() {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE `status` = 'ativo' AND parent_id IS NULL");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deletarGrupo($id) {
        return $this->pdo->prepare("UPDATE {$this->table} SET `status` = 'inativo' WHERE id = ? OR parent_id = ?")->execute([$id, $id]);
    }
}
