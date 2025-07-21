<?php include 'views/template.php';?>
    <div class="container">
        <h2 class="my-4">Lista de Produtos</h2>
        
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
                                <a href='/produto/comprar/{$produto_item['id']}' class='btn btn-success btn-sm'>Comprar</a>
                                <a href='/produto/editar/{$produto_item['id']}' class='btn btn-primary btn-sm'>Editar</a>
                                <a href='/produto/deletar/{$produto_item['id']}' class='btn btn-danger btn-sm'>Deletar</a>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>    
    <a href="/produto/cadastrar" class="btn btn-link mt-3">Cadastrar Produto</a>
    <script src="../../assets/js/script.js"></script>
</body>
</html>
