<?php
$titulo = 'Gestão Comercial';
require 'app/views/layout/header.php';

$resumo = [
    ['Unidades em stock', number_format($totalUnidades, 0, ',', ' '), 'bi-box-seam'],
    ['Valor em stock', number_format($valorStock, 2, ',', ' ') . ' €', 'bi-currency-euro'],
    ["Stock baixo (< $stockMinimo)", $stockBaixo, 'bi-exclamation-triangle'],
];
?>

<?php require 'app/views/layout/tab_comercial.php'; ?>

<div class="row g-3 mb-4">
    <?php foreach ($resumo as $r): ?>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon"><i class="bi <?= $r[2] ?>"></i></div>
                    <div>
                        <div class="fs-4 fw-bold"><?= $r[1] ?></div>
                        <small class="text-muted"><?= $r[0] ?></small>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="row g-4">
    <div class="<?= $podeGerir ? 'col-lg-8' : 'col-12' ?>">
        <div class="card border-0 shadow-sm">
            <form method="get" class="card-body d-flex gap-2">
                <input type="hidden" name="page" value="stock">
                <div class="input-group" style="max-width: 260px;">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Produto ou categoria..." value="<?= htmlspecialchars($search) ?>">
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Produto</th>
                            <th>Categoria</th>
                            <th class="text-end">Preço</th>
                            <th class="text-end">Stock</th>
                            <th>Situação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!$produtos): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Nenhum produto encontrado.</td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach ($produtos as $p): ?>
                            <tr>
                                <td>
                                    <?= htmlspecialchars($p['nome']) ?>
                                    <?php if ($p['estado'] !== 'Ativo'): ?>
                                        <span class="badge text-bg-secondary">Inativo</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($p['categoria']) ?></td>
                                <td class="text-end text-nowrap"><?= number_format($p['preco_venda'], 2, ',', ' ') ?> €</td>
                                <td class="text-end fw-semibold"><?= $p['stock_unidades'] ?></td>
                                <td>
                                    <?php if ($p['stock_unidades'] == 0): ?>
                                        <span class="badge text-bg-danger">Esgotado</span>
                                    <?php elseif ($p['stock_unidades'] < $stockMinimo): ?>
                                        <span class="badge text-bg-warning">Stock baixo</span>
                                    <?php else: ?>
                                        <span class="badge text-bg-success">Disponível</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white text-muted small">A mostrar <?= count($produtos) ?> registos</div>
        </div>
    </div>

    <?php if ($podeGerir): ?>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">Entrada de stock</div>
            <form method="post" action="index.php?page=stock&action=entrada" class="card-body">
                <div class="mb-3">
                    <label for="id_produto" class="form-label">Produto *</label>
                    <select id="id_produto" name="id_produto" class="form-select" required>
                        <option value="">Selecione...</option>
                        <?php foreach ($produtos as $p): ?>
                            <option value="<?= $p['id_produto'] ?>"><?= htmlspecialchars($p['nome']) ?> (<?= $p['stock_unidades'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="quantidade" class="form-label">Quantidade a adicionar *</label>
                    <input type="number" id="quantidade" name="quantidade" min="1" step="1" class="form-control" required>
                </div>
                <button class="btn btn-primary"><i class="bi bi-box-arrow-in-down"></i> Registar entrada</button>
            </form>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php require 'app/views/layout/footer.php'; ?>