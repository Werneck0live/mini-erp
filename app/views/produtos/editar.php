<?php include '../views/template.php'; ?>
    <div class="container mt-5">
        <h2>Editar Produto</h2>

            <form action="/produto/atualizar/<?php echo $produto['id']; ?>" method="POST">
                <!-- Produto Pai -->
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome do Produto</label>
                    <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($produto['nome']) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="preco" class="form-label">Preço</label>
                    <input type="number" class="form-control" id="preco" name="preco" min="0" value="<?= htmlspecialchars($produto['preco']) ?>" step="0.01" required>
                </div>

                <!-- Variações -->
                <hr>
                <h5>Variações</h5>
                <div id="variacoes-container">
                    <?php if (!empty($produto['variacoes'])): ?>
                        <?php foreach ($produto['variacoes'] as $index => $var): ?>
                            <div class="row mb-2 variacao-item">
                                <input type="hidden" name="variacoes[<?= $index ?>][id]" value="<?= $var['id'] ?>">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="variacoes[<?= $index ?>][descricao]" placeholder="Descrição" value="<?= htmlspecialchars($var['descricao']) ?>" required>
                                </div>
                                <div class="col-md-4">
                                    <input type="number" class="form-control" name="variacoes[<?= $index ?>][estoque]" placeholder="Estoque" value="<?= $var['quantidade'] ?>" required>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger remove-variacao">Remover</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <button type="button" class="btn btn-secondary my-3" id="add-variacao">+ Adicionar Variação</button>

                <div id="submit-container" style="display: <?= !empty($produto['variacoes']) ? 'block' : 'none' ?>;">
                    <button type="submit" class="btn btn-primary">Atualizar Produto</button>
                </div>
            </form>

            <a href="/produto/listarTodos" class="btn btn-link mt-3">Voltar para a lista</a>
        </div>

        <script src="../../assets/js/libs/bootstrap.bundle.min.js"></script>
        <script>let variacaoIndex = <?= !empty($produto['variacoes']) ? count($produto['variacoes']) : 0 ?>;</script>
        <script src="../../assets/js/modules/produtos/editar.toggle.submit.button.js"></script>
    </body>
</html>
