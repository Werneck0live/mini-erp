<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Produto</title>
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
     
</head>
<body>
    <div class="container">
        <h1 class="my-4">Cadastro de Produto</h1>
        <!-- <form action="../../controllers/ProdutoController.php" method="POST"> -->
        <form action="/produto/salvar" method="POST">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome do Produto</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
            </div>
            <div class="mb-3">
                <label for="preco" class="form-label">Preço</label>
                <input type="number" class="form-control" id="preco" name="preco" step="0.01" required>
            </div>
            <div class="mb-3">
                <label for="variacoes" class="form-label">Variações</label>
                <textarea class="form-control" id="variacoes" name="variacoes"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Cadastrar Produto</button>
        </form>
    </div>
</body>
</html>
