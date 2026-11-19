<?php
$titulo = 'Dashboard';
require 'app/views/layout/header.php';

$cards = [
    ['Salinas', $totais['salinas'], 'bi-water'],
    ['Trabalhadores', $totais['trabalhadores'], 'bi-people'],
    ['Colheitas', $totais['colheitas'], 'bi-basket'],
    ['Unidades em stock', $totais['qtd_produtos'], 'bi-box-seam'],
    ['Produtos', $totais['produtos'], 'bi-tag'],
    ['Clientes', $totais['clientes'], 'bi-person-vcard'],
    ['Encomendas', $totais['encomendas'], 'bi-cart3']
];
?>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <h4 class="mb-1">Olá, <?= htmlspecialchars($_SESSION['nome']) ?>
            <span class="badge text-bg-<?= $corPerfil[$_SESSION['perfil']] ?? 'secondary' ?>"><?= $_SESSION['perfil'] ?></span>
        </h4>
        <small class="text-muted"><i class="bi bi-clock-history"></i> Última autenticação:
            <?= $_SESSION['ultimo_acesso'] ? date('d/m/Y H:i', strtotime($_SESSION['ultimo_acesso'])) : 'Primeiro acesso' ?>
        </small>
    </div>
</div>

<div class="row g-3">
    <?php foreach ($cards as $card): ?>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon"><i class="bi <?= $card[2] ?>"></i></div>
                    <div>
                        <div class="fs-3 fw-bold"><?= $card[1] ?></div>
                        <small class="text-muted"><?= $card[0] ?></small>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="row g-3 mt-1">
    <div class="col-lg-4" data-carregar="index.php?page=dashboard&action=api&bloco=meteo">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white fw-semibold"><i class="bi bi-cloud-sun"></i> Meteorologia</div>
            <div class="card-body text-center text-muted py-5"><div class="spinner-border spinner-border-sm"></div> A carregar...</div>
        </div>
    </div>

    <div class="col-lg-4" data-carregar="index.php?page=dashboard&action=api&bloco=precos">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white fw-semibold"><i class="bi bi-currency-euro"></i> Preço de referência</div>
            <div class="card-body text-center text-muted py-5"><div class="spinner-border spinner-border-sm"></div> A carregar...</div>
        </div>
    </div>

    <div class="col-lg-4" data-carregar="index.php?page=dashboard&action=api&bloco=alertas">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white fw-semibold"><i class="bi bi-exclamation-octagon"></i> Alertas</div>
            <div class="card-body text-center text-muted py-5"><div class="spinner-border spinner-border-sm"></div> A carregar...</div>
        </div>
    </div>

</div>

<?php require 'app/views/layout/footer.php'; ?>
