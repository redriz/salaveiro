<div class="card border-0 shadow-sm h-100">
    <div class="card-header bg-white fw-semibold"><i class="bi bi-cloud-sun"></i> Meteorologia</div>
    <div class="card-body">
        <?php if ($meteo): ?>
            <div class="fs-2 fw-bold"><?= number_format($meteo['temperature'], 1, ',', ' ') ?> °C</div>
            <div class="text-muted mb-3"><?= htmlspecialchars($meteo['condition']) ?> · <?= htmlspecialchars($meteo['location']) ?></div>
            <ul class="list-unstyled small mb-3">
                <li><i class="bi bi-droplet"></i> Humidade: <?= $meteo['humidity'] ?>%</li>
                <li><i class="bi bi-wind"></i> Vento: <?= $meteo['windSpeedKmh'] ?> km/h <?= htmlspecialchars($meteo['windDirection']) ?></li>
                <li><i class="bi bi-cloud-rain"></i> Precipitação: <?= number_format($meteo['precipitationMm'], 1, ',', ' ') ?> mm</li>
                <li><i class="bi bi-cloud"></i> Nebulosidade: <?= $meteo['cloudCoverPercent'] ?>%</li>
                <li><i class="bi bi-sun"></i> Índice de evaporação: <?= number_format($meteo['evaporationIndex'], 1, ',', ' ') ?></li>
            </ul>
            <small class="text-muted">Atualizado: <?= date('d/m/Y H:i', strtotime($meteo['lastUpdate'])) ?></small>
        <?php else: ?>
            <?= $erroApi ?>
        <?php endif; ?>
    </div>
</div>
