<?php include '../views/template.php'; ?>
        <div class="container">
            <h2 class="my-4">Cadastro de Produto</h2>

            <form action="/produto/salvar" method="POST">
                <!-- Produto Pai -->
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome do Produto</label>
                    <input type="text" class="form-control" id="nome" name="nome" required>
                </div>
                <div class="mb-3">
                    <label for="preco" class="form-label">Preço</label>
                    <input type="number" class="form-control" id="preco" name="preco" min="0" step="0.01" required>
                </div>

                <hr>
                <h5>Variações</h5>
                <div id="variacoes-container"></div>

                <button type="button" class="btn btn-secondary my-2" onclick="adicionarVariacao()">+ Adicionar Variação</button>

                <hr>
                <button type="submit" id="btnCadastrar" class="btn btn-primary d-none">Cadastrar Produto</button>
            </form>
        </div>

        <script src="../../assets/js/modules/produtos/cadastrar.toggle.submit.button.js"></script>
    </body>
</html>