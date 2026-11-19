<div class="card border-0 shadow-sm h-100">
    <div class="card-header bg-white fw-semibold d-flex justify-content-between">
        <span><i class="bi bi-currency-euro"></i> Preço de referência</span>
        <?php if ($precos): ?>
            <small class="text-muted fw-normal"><?= date('d/m/Y', strtotime(reset($precos)['date'])) ?></small>
        <?php endif; ?>
    </div>
    <?php if ($precos): ?>
        <table class="table table-sm align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Produto</th>
                    <th class="text-end">€/kg</th>
                    <th>Mercado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($precos as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['product']) ?></td>
                        <td class="text-end text-nowrap"><?= number_format($p['priceKg'], 2, ',', ' ') ?> €</td>
                        <td><?= htmlspecialchars($p['market']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="card-body"><?= $erroApi ?></div>
    <?php endif; ?>
</div>
