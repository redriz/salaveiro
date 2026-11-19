<div class="card border-0 shadow-sm h-100">
    <div class="card-header bg-white fw-semibold d-flex justify-content-between">
        <span><i class="bi bi-exclamation-octagon"></i> Alertas</span>
        <?php if ($alertas !== null): ?>
            <span class="badge text-bg-danger"><?= count($alertas) ?> ativos</span>
        <?php endif; ?>
    </div>
    <?php if ($alertas === null): ?>
        <div class="card-body"><?= $erroApi ?></div>
    <?php elseif (!$alertas): ?>
        <div class="card-body text-muted">Sem alertas ativos.</div>
    <?php else: ?>
        <ul class="list-group list-group-flush" style="max-height: 300px; overflow-y: auto;">
            <?php foreach ($alertas as $a): ?>
                <li class="list-group-item">
                    <span class="badge text-bg-<?= $corNivel[$a['level']] ?? 'secondary' ?>"><?= htmlspecialchars($a['level']) ?></span>
                    <small class="text-muted"><?= htmlspecialchars($a['type']) ?> · <?= date('d/m/Y H:i', strtotime($a['createdAt'])) ?></small>
                    <div class="small fw-semibold mt-1"><?= htmlspecialchars($a['title']) ?></div>
                    <div class="small"><?= htmlspecialchars($a['message']) ?></div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
