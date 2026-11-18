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

$corNivel = ['Baixo' => 'info', 'Médio' => 'warning', 'Elevado' => 'danger', 'Crítico' => 'dark'];

$erroApi = '<div class="alert alert-danger mb-0"><i class="bi bi-exclamation-triangle"></i> <strong>Erro de comunicação.</strong> Não foi possível contactar a API ou a resposta é inválida.</div>';
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

    <div class="col-lg-4">
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
    </div>

    <div class="col-lg-4">
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
    </div>

    <div class="col-lg-4">
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
    </div>

</div>

<?php require 'app/views/layout/footer.php'; ?>