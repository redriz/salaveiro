<?php
$titulo = 'Consulta de Dados Externos';
require 'app/views/layout/header.php';

function valorCelula($v)
{
    if (is_bool($v)) {
        return $v ? 'Sim' : 'Não';
    }
    if (is_array($v)) {
        return json_encode($v, JSON_UNESCAPED_UNICODE);
    }
    if (is_float($v)) {
        return number_format($v, 2, ',', ' ');
    }
    return $v ?? '—';
}
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
    <div class="row g-4">

        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <span class="fw-semibold">Resultados</span>
                    <?php if ($resposta): ?>
                        <small class="text-muted">
                            <span class="badge text-bg-<?= $resposta['status'] === 200 ? 'success' : 'danger' ?>"><?= $resposta['status'] ?> <?= $resposta['status'] === 200 ? 'OK' : 'Erro' ?></span>
                            <?= $resposta['tempo'] ?> ms · JSON
                        </small>
                    <?php endif; ?>
                </div>

                <?php if ($erro): ?>
                    <div class="card-body text-muted">Sem resultados.</div>
                <?php else: ?>
                    <div class="table-responsive" style="max-height: 520px;">
                        <table class="table table-sm table-hover align-middle mb-0 small">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <?php foreach ($colunas as $col): ?>
                                        <th><?= htmlspecialchars($col) ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!$linhas): ?>
                                    <tr>
                                        <td class="text-center text-muted py-4">Nenhum registo corresponde à pesquisa.</td>
                                    </tr>
                                <?php endif; ?>

                                <?php foreach ($linhas as $linha): ?>
                                    <tr>
                                        <?php foreach ($colunas as $col): ?>
                                            <td><?= htmlspecialchars(valorCelula($linha[$col] ?? null)) ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer bg-white text-muted small"><?= count($linhas) ?> registos recebidos</div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-lg-5">
            <?php if ($resposta && $resposta['dados'] !== null): ?>
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white fw-semibold">Resposta (JSON)</div>
                    <pre class="json-box mb-0"><?= htmlspecialchars(json_encode($resposta['dados'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) ?></pre>
                </div>
            <?php endif; ?>

            <?php if ($erro): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i> <strong>Erro de comunicação.</strong> <?= htmlspecialchars($erro) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<?php require 'app/views/layout/footer.php'; ?>