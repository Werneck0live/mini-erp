<?php include 'views/template.php'; ?>
<div class="container">
    <h2 class="my-4">Cadastro de Produto</h2>
    <form method="POST" action="/cupom/salvar">
        <div class="mb-3">
            <label>Código:</label>
            <input type="text" name="codigo" required><br>
        </div>
        <div class="mb-3">
            <label>Percentual do desconto:</label>
            <input type="number" name="percentual_desconto" step="0.01" required><br>
        </div>
        <div class="mb-3">
            <label>Valor Mínimo:</label>
            <input type="number" name="valor_minimo" step="0.01"><br>
        </div>
        <div class="mb-3">
            <label>Validade:</label>
            <input type="date" name="validade" required><br>
        </div>

        <button type="submit" class="btn btn-primary">Salvar</button>
    </form>
</div>
<hr>

<h3>Cupons Existentes</h3>
<ul>
<?php
foreach ($cupons as $cupom):
?>
    <li><strong><?= $cupom['codigo'] ?></strong> | <?= $cupom['percentual'] ?>% de desconto até <?= $cupom['validade'] ?></li>
<?php endforeach; ?>
</ul>
</body>
</html>
