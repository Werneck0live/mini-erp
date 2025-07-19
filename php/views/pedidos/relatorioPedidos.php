<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resumo do Pedido</title>
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="my-4">Resumo do Pedido</h1>
        
        <div class="row">
            <div class="col">
                <p><strong>Subtotal:</strong> R$ <?= number_format($subtotal, 2, ',', '.') ?></p>
                <p><strong>Frete:</strong> R$ <?= number_format($frete, 2, ',', '.') ?></p>
                <p><strong>Total:</strong> R$ <?= number_format($total, 2, ',', '.') ?></p>
            </div>
        </div>
        
        <form action="finalizar.php" method="POST">
            <div class="mb-3">
                <label for="cep" class="form-label">CEP de Entrega</label>
                <input type="text" class="form-control" id="cep" name="cep" required>
            </div>
            <button type="submit" class="btn btn-primary">Finalizar Pedido</button>
        </form>
    </div>
    
    <script src="../../assets/js/script.js"></script>
</body>
</html>
