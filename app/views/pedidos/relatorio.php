<?php include '../views/template.php'; ?>

<h3>Relatório de Pedidos</h3>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>#ID</th>
            <th>Status</th>
            <th>Subtotal</th>
            <th>Frete</th>
            <th>Desconto</th>
            <th>Total</th>
            <th>CEP</th>
            <th>Endereço</th>
            <th>Email</th>
            <th>Data</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($pedidos as $pedido): ?>
        <tr>
            <?php
                $status = ($pedido['status']=='inativo') 
                    ? "<strong style='color: red;'>Cancelado</strong>"
                    : "<strong>".ucfirst($pedido['status'])."</strong>";
            ?>

            <td><?= $pedido['id'] ?></td>
            <td><?= $status ?></td>
            <td>R$ <?= number_format($pedido['subtotal'], 2, ',', '.') ?></td>
            <td>R$ <?= number_format($pedido['frete'], 2, ',', '.') ?></td>
            <td>R$ <?= number_format($pedido['valor_desconto'], 2, ',', '.') ?></td>
            <td><strong>R$ <?= number_format($pedido['total'], 2, ',', '.') ?></strong></td>
            <td><?= $pedido['cep'] ?></td>
            <td><?= $pedido['endereco'] ?></td>
            <td><?= $pedido['email_cliente'] ?></td>
            <td><?= date('d/m/Y H:i', strtotime($pedido['data_criacao'])) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Paginação Bootstrap -->
<nav>
    <ul class="pagination justify-content-center">
        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
            <li class="page-item <?= ($i == $pagina) ? 'active' : '' ?>">
                <a class="page-link" href="?pagina=<?= $i ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>
    </ul>
</nav>