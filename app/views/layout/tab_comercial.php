<?php
$tabs = [
    'encomendas' => 'Encomendas',
    'clientes' => 'Clientes',
    'stock' => 'Produtos e Stock',
];
$tabAtual = $_GET['page'] ?? 'encomendas';
if ($tabAtual === 'comercial') {
    $tabAtual = 'encomendas';
}
?>
<ul class="nav nav-tabs mb-4">
    <?php foreach ($tabs as $key => $label): ?>
        <li class="nav-item">
            <a class="nav-link <?= $tabAtual === $key ? 'active' : '' ?>" href="index.php?page=<?= $key ?>"><?= $label ?></a>
        </li>
    <?php endforeach; ?>
</ul>