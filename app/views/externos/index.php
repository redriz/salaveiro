<?php
$titulo = 'Consulta de Dados Externos';
require 'app/views/layout/header.php';
?>

<div class="card border-0 shadow-sm mb-4">
    <form method="get" class="card-body row g-3 align-items-end">
        <input type="hidden" name="page" value="externos">
        <div class="col-md-4">
            <label for="recurso" class="form-label">Recurso da API</label>
            <select id="recurso" name="recurso" class="form-select" required>
                <option value="">Selecione...</option>
                <?php foreach ($recursos as $key => $label): ?>
                    <option value="<?= $key ?>" <?= $recurso === $key ? 'selected' : '' ?>>/api/<?= $key ?> · <?= $label ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-5">
            <label for="pesquisa" class="form-label">Pesquisa</label>
            <input type="text" id="pesquisa" name="pesquisa" class="form-control" placeholder="ID (ex.: 3) ou texto (ex.: Crítico)"
                value="<?= htmlspecialchars($pesquisa) ?>">
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary w-100"><i class="bi bi-cloud-download"></i> Consultar</button>
        </div>
    </form>
</div>

<?php if ($recurso !== ''): ?>
    <div data-carregar="index.php?page=externos&action=api&recurso=<?= urlencode($recurso) ?>&pesquisa=<?= urlencode($pesquisa) ?>">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center text-muted py-5"><div class="spinner-border spinner-border-sm"></div> A consultar a API...</div>
        </div>
    </div>
<?php endif; ?>

<?php require 'app/views/layout/footer.php'; ?>