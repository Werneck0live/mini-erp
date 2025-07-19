<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Produtos</title>
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="my-4">Lista de Produtos</h1>
        <a href="cadastro.php" class="btn btn-success mb-3">Cadastrar Novo Produto</a>

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Nome</th>
                    <th scope="col">Preço</th>
                    <th scope="col">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                
                foreach ($produtos as $produto_item) {
                    echo "<tr>
                            <td name='name'>{$produto_item['nome']}</td>
                            <td name='preco'>R$ " . number_format($produto_item['preco'], 2, ',', '.') . "</td>
                            <td>
                                <a href='/produto/carregarproduto/{$produto_item['id']}' class='btn btn-primary btn-sm'>Editar</a>
                                <a href='/produto/deletar/{$produto_item['id']}' class='btn btn-danger btn-sm'>Deletar</a>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    
    <script src="../../assets/js/script.js"></script>
</body>
</html>
