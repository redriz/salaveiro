<?php
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
