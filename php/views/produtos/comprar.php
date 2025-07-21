<?php include 'views/template.php';

?>
<div class="container mt-5">
    <h2>Adicionar produto ao carrinho</h2>
    <form action="/pedido/adicionar/<?php echo $produto['id']; ?>" method="POST">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome do Produto</label>
            <input type="text" class="form-control" id="nome" name="nome" value="<?php echo $produto['nome']; ?>" disabled>
        </div>
        
        <div class="mb-3">
            <label for="preco" class="form-label">Preço</label>
            <input type="number" step="0.01" class="form-control" id="preco" name="preco" value="<?php echo $produto['preco']; ?>" disabled>
        </div>
        
        <div class="mb-3">
            <label for="estoque" class="form-label">Disponível</label>
            <input type="text" class="form-control" id="estoque" name="estoque" value="<?php echo $produto['quantidade']; ?>" disabled>
        </div>

        <div class="mb-3">
            <label for="quantidade" class="form-label">Quantidade</label>
            <input type="number" class="form-control" id="quantidade" name="quantidade" max="<?php echo $produto['quantidade']; ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Adicionar</button>
    </form>
    <a href="/produto/listar" class="btn btn-link mt-5">Voltar para a lista</a>

</div>

<script src="../../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
