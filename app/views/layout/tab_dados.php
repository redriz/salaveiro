<?php
$tabs = [
    'utilizadores'  => 'Utilizadores',
    'trabalhadores' => 'Trabalhadores',
    'salinas'       => 'Salinas e Tanques',
    'categorias'    => 'Categorias e Produtos',
];
$tabAtual = $_GET['page'] ?? 'utilizadores';
if ($tabAtual === 'dados') {
    $tabAtual = 'utilizadores';
}
?>
<ul class="nav nav-tabs mb-4">
    <?php foreach ($tabs as $key => $label): ?>
        <li class="nav-item">
            <a class="nav-link <?= $tabAtual === $key ? 'active' : '' ?>" href="index.php?page=<?= $key ?>">
                <?= $label ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>