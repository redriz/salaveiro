<?php
$titulo = 'Registo de Produção';
require 'app/views/layout/header.php';

$tanquesPorSalina = [];
foreach ($tanques as $t) {
    $tanquesPorSalina[$t['salina']][] = $t;
}

$totalKg = array_sum(array_column($colheitas, 'quantidade_kg'));
?>

<div class="row g-4">

    <div class="col-lg-4">
        <?php if ($detalhe): ?>
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">Registo de <?= date('d/m/Y', strtotime($detalhe['data_colheita'])) ?></div>
            <div class="card-body">
                <dl class="row small mb-3">
                    <dt class="col-5 text-muted fw-normal">ID</dt>
                    <dd class="col-7 text-end"><?= $detalhe['id_colheita'] ?></dd>
                    <dt class="col-5 text-muted fw-normal">Salina</dt>
                    <dd class="col-7 text-end"><?= htmlspecialchars($detalhe['salina']) ?></dd>
                    <dt class="col-5 text-muted fw-normal">Tanque</dt>
                    <dd class="col-7 text-end"><?= htmlspecialchars($detalhe['tanque']) ?></dd>
                    <dt class="col-5 text-muted fw-normal">Trabalhador</dt>
                    <dd class="col-7 text-end"><?= htmlspecialchars($detalhe['trabalhador']) ?></dd>
                    <dt class="col-5 text-muted fw-normal">Quantidade</dt>
                    <dd class="col-7 text-end"><?= number_format($detalhe['quantidade_kg'], 2, ',', ' ') ?> kg</dd>
                    <dt class="col-5 text-muted fw-normal">Registado por</dt>
                    <dd class="col-7 text-end"><?= htmlspecialchars($detalhe['registado_por']) ?></dd>
                    <dt class="col-5 text-muted fw-normal">Registado em</dt>
                    <dd class="col-7 text-end"><?= date('d/m/Y H:i', strtotime($detalhe['criado_em'])) ?></dd>
                </dl>
                <h6 class="small text-muted">Observações</h6>
                <p class="small mb-3" style="white-space: pre-line;"><?= htmlspecialchars($detalhe['observacoes'] ?: 'Sem observações.') ?></p>
                <?php if ($podeGerir): ?>
                    <a href="index.php?page=producao&id=<?= $detalhe['id_colheita'] ?>&salina=<?= urlencode($idSalina) ?>" class="btn btn-primary"><i class="bi bi-pencil"></i> Editar</a>
                <?php endif; ?>
                <a href="index.php?page=producao&salina=<?= urlencode($idSalina) ?>" class="btn btn-outline-secondary">Fechar</a>
            </div>
        </div>
        <?php elseif (!$podeGerir): ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body text-muted text-center py-5">
                <i class="bi bi-eye fs-2 d-block mb-2"></i>
                Selecione um registo para ver o detalhe.
            </div>
        </div>
        <?php else: ?>
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">
                <?= $colheita['id_colheita'] ? 'Editar registo de produção' : 'Novo registo de produção' ?>
            </div>
            <form method="post" action="index.php?page=producao&id=<?= $colheita['id_colheita'] ?>" class="card-body">

                <?php if (isset($erros['geral'])): ?>
                    <div class="alert alert-danger py-2"><?= $erros['geral'] ?></div>
                <?php endif; ?>

                <div class="mb-3">
                    <label for="id_tanque" class="form-label">Salina / Tanque *</label>
                    <select id="id_tanque" name="id_tanque" class="form-select <?= isset($erros['id_tanque']) ? 'is-invalid' : '' ?>">
                        <option value="">Selecione...</option>
                        <?php foreach ($tanquesPorSalina as $nomeSalina => $lista): ?>
                            <optgroup label="<?= htmlspecialchars($nomeSalina) ?>">
                                <?php foreach ($lista as $t): ?>
                                    <option value="<?= $t['id_tanque'] ?>"
                                        <?= $t['id_tanque'] == $colheita['id_tanque'] ? 'selected' : '' ?>
                                        <?= $t['estado'] !== 'Ativo' ? 'disabled' : '' ?>>
                                        <?= htmlspecialchars($t['codigo']) ?> (<?= mb_strtolower($t['estado']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </optgroup>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback"><?= $erros['id_tanque'] ?? '' ?></div>
                </div>

                <div class="mb-3">
                    <label for="id_trabalhador" class="form-label">Trabalhador responsável *</label>
                    <select id="id_trabalhador" name="id_trabalhador" class="form-select <?= isset($erros['id_trabalhador']) ? 'is-invalid' : '' ?>">
                        <option value="">Selecione...</option>
                        <?php foreach ($trabalhadores as $tr): ?>
                            <option value="<?= $tr['id_trabalhador'] ?>" <?= $tr['id_trabalhador'] == $colheita['id_trabalhador'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($tr['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback"><?= $erros['id_trabalhador'] ?? '' ?></div>
                </div>

                <div class="row">
                    <div class="col-6 mb-3">
                        <label for="data_colheita" class="form-label">Data *</label>
                        <input type="date" id="data_colheita" name="data_colheita" max="<?= date('Y-m-d') ?>"
                            class="form-control <?= isset($erros['data_colheita']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($colheita['data_colheita']) ?>">
                        <div class="invalid-feedback"><?= $erros['data_colheita'] ?? '' ?></div>
                    </div>
                    <div class="col-6 mb-3">
                        <label for="quantidade_kg" class="form-label">Quantidade (kg) *</label>
                        <input type="number" step="0.01" min="0.01" id="quantidade_kg" name="quantidade_kg"
                            class="form-control <?= isset($erros['quantidade_kg']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($colheita['quantidade_kg']) ?>">
                        <div class="invalid-feedback"><?= $erros['quantidade_kg'] ?? '' ?></div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="observacoes" class="form-label">Observações</label>
                    <textarea id="observacoes" name="observacoes" rows="3" maxlength="500" class="form-control"><?= htmlspecialchars($colheita['observacoes'] ?? '') ?></textarea>
                </div>

                <button class="btn btn-primary"><?= $colheita['id_colheita'] ? 'Guardar' : 'Registar' ?></button>
                <a href="index.php?page=producao" class="btn btn-outline-secondary">Limpar</a>
            </form>
        </div>
        <?php endif; ?>
    </div>

    <div class="col-lg-8">

        <!-- Estado dos tanques -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold">Estado dos tanques</div>
            <div class="card-body d-flex flex-wrap gap-2">
                <?php foreach ($tanques as $t): ?>
                    <span class="badge text-bg-<?= $corEstado[$t['estado']] ?> p-2" title="<?= htmlspecialchars($t['salina']) ?>">
                        <?= htmlspecialchars($t['codigo']) ?> · <?= mb_strtolower($t['estado']) ?>
                    </span>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Registos recentes -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="fw-semibold">Registos recentes</span>
                <form method="get">
                    <input type="hidden" name="page" value="producao">
                    <select name="salina" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">Todas as salinas</option>
                        <?php foreach ($salinas as $s): ?>
                            <option value="<?= $s['id_salina'] ?>" <?= $s['id_salina'] == $idSalina ? 'selected' : '' ?>>
                                <?= htmlspecialchars($s['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 table-acoes">
                    <thead class="table-light">
                        <tr>
                            <th>Data</th>
                            <th>Salina</th>
                            <th>Tanque</th>
                            <th>Trabalhador</th>
                            <th class="text-end">Kg</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!$colheitas): ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Nenhum registo de produção encontrado.</td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach ($colheitas as $c): ?>
                            <tr class="<?= $detalhe && $detalhe['id_colheita'] == $c['id_colheita'] ? 'table-active' : '' ?>">
                                <td><?= date('d/m/Y', strtotime($c['data_colheita'])) ?></td>
                                <td><?= htmlspecialchars($c['salina']) ?></td>
                                <td><?= htmlspecialchars($c['tanque']) ?></td>
                                <td><?= htmlspecialchars($c['trabalhador']) ?></td>
                                <td class="text-end"><?= number_format($c['quantidade_kg'], 2, ',', ' ') ?></td>
                                <td class="text-nowrap">
                                    <a href="index.php?page=producao&ver=<?= $c['id_colheita'] ?>&salina=<?= urlencode($idSalina) ?>" class="btn btn-sm btn-outline-secondary" title="Ver detalhe"><i class="bi bi-eye"></i></a>
                                    <?php if ($podeGerir): ?>
                                    <a href="index.php?page=producao&id=<?= $c['id_colheita'] ?>&salina=<?= $idSalina ?>" class="btn btn-sm btn-outline-primary" title="Editar"><i class="bi bi-pencil"></i></a>
                                    <form method="post" action="index.php?page=producao&action=delete" class="d-inline">
                                        <input type="hidden" name="id" value="<?= $c['id_colheita'] ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar" data-confirmar="Eliminar este registo?" data-confirmar-texto="Esta ação não pode ser desfeita." data-confirmar-botao="Eliminar"><i class="bi bi-trash"></i></button>
                                    </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white text-muted small d-flex justify-content-between">
                <span>A mostrar <?= count($colheitas) ?> registos</span>
                <span>Total: <strong><?= number_format($totalKg, 2, ',', ' ') ?> kg</strong></span>
            </div>
        </div>
    </div>
</div>

<?php require 'app/views/layout/footer.php'; ?>