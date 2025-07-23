<?php include '../views/template.php'; ?>
<div class="container mt-5">
    <h2>Adicionar produto ao carrinho</h2>

    <!-- Produto Pai -->
    <div class="mb-3">
        <label class="form-label">Nome do Produto</label>
        <input type="text" class="form-control" value="<?= htmlspecialchars($produto['nome']) ?>" disabled>
    </div>

    <div class="mb-3">
        <label class="form-label">Preço (R$)</label>
        <input type="text" class="form-control" value="<?= number_format($produto['preco'], 2, ',', '.') ?>" disabled>
    </div>

    <form action="/pedido/adicionar/<?= $produto['id']; ?>" method="POST">
        <h5 class="mt-4">Selecione as Variações</h5>

        <?php if (!empty($produto['variacoes'])): ?>
            <?php foreach ($produto['variacoes'] as $index => $var): ?>
                <div class="border p-3 mb-3 rounded variacao-bloco">
                    <input type="hidden" name="variacoes[<?= $index ?>][id]" value="<?= $var['id'] ?>">

                    <div class="row align-items-center">
                        <!-- Checkbox -->
                        <div class="col-md-2">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input toggle-variacao" id="check-<?= $index ?>" data-index="<?= $index ?>">
                                <label class="form-check-label" for="check-<?= $index ?>">
                                    <strong><?= htmlspecialchars($var['descricao']) ?></strong>
                                </label>
                            </div>
                        </div>

                        <!-- Disponível -->
                        <div class="col-md-3">
                            <label class="form-label mb-0">Disponível</label>
                            <input type="number" class="form-control" value="<?= $var['quantidade'] ?>" disabled>
                        </div>

                        <!-- Quantidade desejada -->
                        <div class="col-md-3">
                            <label class="form-label mb-0">Quantidade Desejada</label>
                            <?php if ($var['quantidade'] == 0): ?>
                                <input type="text" class="form-control" value="Indisponível" readonly>
                            <?php else: ?>
                                <input type="number"
                                       class="form-control quantidade-input"
                                       name="variacoes[<?= $index ?>][quantidade]"
                                       id="quantidade-<?= $index ?>"
                                       max="<?= $var['quantidade'] ?>"
                                       min="1"
                                       value="1"
                                       disabled
                                       required>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-danger">Este produto não possui variações disponíveis no momento.</p>
        <?php endif; ?>

        <?php if (!empty($produto['variacoes'])): ?>
            <div id="submit-container" style="display: none;">
                <button type="submit" class="btn btn-primary">Adicionar ao Carrinho</button>
            </div>
        <?php endif; ?>
    </form>

    <a href="/produto/listarTodos" class="btn btn-link mt-4">Voltar para a lista</a>
</div>

<script src="../../assets/js/libs/bootstrap.bundle.min.js"></script>
<script src="../../assets/js/modules/produtos/comprar.js"></script>
<script src="../../assets/js/modules/produtos/comprar.toggle.submit.button.js"></script>
</body>
</html>
