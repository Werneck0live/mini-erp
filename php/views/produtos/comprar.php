<?php include 'views/template.php';

?>
<div class="container mt-5">
    <h2>Adicionar produto ao carrinho</h2>
    <form action="/pedido/adicionar/<?php echo $produto['id']; ?>" method="POST">
        <div class="mb-3">
            <label for="nome" class="form-label" data-estoque="<?= $item['estoque'] ?>">Nome do Produto</label>
            <input type="text" class="form-control" id="nome" name="nome" value="<?php echo $produto['nome']; ?>" disabled>
        </div>
        
        <div class="mb-3">
            <label for="preco" class="form-label" data-estoque="<?= $item['estoque'] ?>">Preço</label>
            <input type="number" step="0.01" class ="form-control" id="preco" name="preco" value="<?php echo $produto['preco']; ?>" disabled>
        </div>
        
        <div class="mb-3">
            <label for="estoque" class="form-label" data-estoque="<?= $item['estoque'] ?>">Disponível</label>
            <input type="text" class="form-control"  id="estoque" name="estoque" value="<?php echo $produto['quantidade']; ?>" disabled>
        </div>

        <div class="mb-3">
            <label for="quantidade" class="form-label" data-estoque="<?= $item['estoque'] ?>">Quantidade</label>
            <?php if($produto['quantidade'] == 0):?>
            <input type="text" class="form-control" id ="quantidade" name="quantidade" title="Saldo insuficiente em estoque!" value="Estoque indisponível" readonly>
            <?php else: ?>
            <input type="number" class="form-control" id ="quantidade" name="quantidade" max="<?php echo $produto['quantidade']; ?>" value="1" required>
            <?php endif; ?>
        </div>
        <?php if($produto['quantidade'] > 0):?>
        <button type="submit" class="btn btn-primary">Adicionar</button>
        <?php endif; ?>
    </form>
    <a href="/produto/listarTodos" class="btn btn-link mt-5">Voltar para a lista</a>

</div>

<script src="../../assets/js/libs/bootstrap.bundle.min.js"></script>
<script src="../../assets/js/modules/produtos/comprar.js"></script>

</body>
</html>
