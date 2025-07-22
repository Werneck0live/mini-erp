<?php include '../views/template.php';?>
    <div class="container">
        <h2 class="my-4">Cadastro de Produto</h2>
        <!-- <form action="../../controllers/ProdutoController.php" method="POST"> -->
        <form action="/produto/salvar" method="POST">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome do Produto</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
            </div>
            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea class="form-control" id="descricao" name="descricao"></textarea>
            </div>
            <div class="mb-3">
                <label for="preco" class="form-label">Preço </label>
                <input type="number" class="form-control" placeholder="R$" id="preco" name="preco" step="0.01" min="0" required>
            </div>
            <div class="mb-3">
                <label for="estoque" class="form-label">Quantidade em estoque</label>
                <input type="number" class="form-control" id="estoque" name="estoque" min="0" required>
            </div>
            <button type="submit" class="btn btn-primary">Cadastrar Produto</button>
        </form>
    </div>
</body>
</html>
