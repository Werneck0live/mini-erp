<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto</title>
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2>Editar Produto</h2>
    <form action="/produto/atualizar/<?php echo $produto['id']; ?>" method="POST">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome do Produto</label>
            <input type="text" class="form-control" id="nome" name="nome" value="<?php echo $produto['nome']; ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="preco" class="form-label">Preço</label>
            <input type="number" step="0.01" class="form-control" id="preco" name="preco" value="<?php echo $produto['preco']; ?>" required>
        </div>

        <div class="mb-3">
            <label for="variacoes" class="form-label">Variações</label>
            <input type="text" class="form-control" id="variacoes" name="variacoes" value="<?php echo $produto['variacoes']; ?>" required>
        </div>

        <div class="mb-3">
            <label for="estoque" class="form-label">Estoque</label>
            <input type="number" class="form-control" id="estoque" name="estoque" value="<?php echo $produto['estoque']; ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Atualizar Produto</button>
    </form>

    <a href="/produtos/lista" class="btn btn-secondary mt-3">Voltar para a lista</a>
</div>

<script src="../../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
