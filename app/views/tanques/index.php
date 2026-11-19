<?php
$titulo = 'Gestão de Dados Principais';
require 'app/views/layout/header.php';
?>

<?php require 'app/views/layout/tab_dados.php'; ?>

<div class="btn-group mb-3">
    <a href="index.php?page=salinas" class="btn btn-outline-primary">Salinas</a>
    <a href="index.php?page=tanques" class="btn btn-primary">Tanques</a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <form method="get" class="card-body d-flex flex-wrap gap-2">
                <input type="hidden" name="page" value="tanques">
                <div class="input-group" style="max-width: 260px;">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Código ou salina..." value="<?= htmlspecialchars($search) ?>">
                </div>
                <select name="estado" class="form-select w-auto" onchange="this.form.submit()">
                    <option value="">Todos os estados</option>
                    <?php foreach (['Ativo', 'Inativo', 'Manutenção'] as $e): ?>
                        <option <?= $estado === $e ? 'selected' : '' ?>><?= $e ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if ($podeGerir): ?>
                    <a href="index.php?page=tanques" class="btn btn-primary ms-auto"><i class="bi bi-plus-lg"></i> Novo tanque</a>
                <?php endif; ?>
            </form>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 table-acoes">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Código</th>
                            <th>Salina</th>
                            <th class="text-end">Área (m²)</th>
                            <th class="text-end">Capacidade (kg)</th>
                            <th>Estado</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!$tanques): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Nenhum tanque encontrado.</td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach ($tanques as $t): ?>
                            <tr class="<?= $detalhe && $detalhe['id_tanque'] == $t['id_tanque'] ? 'table-active' : '' ?>">
                                <td><?= $t['id_tanque'] ?></td>
                                <td><?= htmlspecialchars($t['codigo']) ?></td>
                                <td><?= htmlspecialchars($t['salina']) ?></td>
                                <td class="text-end"><?= number_format($t['area_m2'], 2, ',', ' ') ?></td>
                                <td class="text-end"><?= number_format($t['capacidade_maxima_kg'], 2, ',', ' ') ?></td>
                                <td><span class="badge text-bg-<?= $corEstado[$t['estado']] ?>"><?= $t['estado'] ?></span></td>
                                <td class="text-nowrap">
                                    <a href="index.php?page=tanques&ver=<?= $t['id_tanque'] ?>&search=<?= urlencode($search) ?>&estado=<?= urlencode($estado) ?>" class="btn btn-sm btn-outline-secondary" title="Ver detalhe"><i class="bi bi-eye"></i></a>
                                    <?php if ($podeGerir): ?>
                                    <a href="index.php?page=tanques&id=<?= $t['id_tanque'] ?>" class="btn btn-sm btn-outline-primary" title="Editar"><i class="bi bi-pencil"></i></a>
                                    <form method="post" action="index.php?page=tanques&action=delete" class="d-inline">
                                        <input type="hidden" name="id" value="<?= $t['id_tanque'] ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar" data-confirmar="Eliminar este tanque?" data-confirmar-texto="Esta ação não pode ser desfeita." data-confirmar-botao="Eliminar"><i class="bi bi-trash"></i></button>
                                    </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white text-muted small">A mostrar <?= count($tanques) ?> registos</div>
        </div>
    </div>
    <div class="col-lg-4">
        <?php if ($detalhe): ?>
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center">
                <span>Tanque <?= htmlspecialchars($detalhe['codigo']) ?></span>
                <span class="badge text-bg-<?= $corEstado[$detalhe['estado']] ?>"><?= $detalhe['estado'] ?></span>
            </div>
            <div class="card-body">
                <dl class="row small mb-3">
                    <dt class="col-5 text-muted fw-normal">ID</dt>
                    <dd class="col-7 text-end"><?= $detalhe['id_tanque'] ?></dd>
                    <dt class="col-5 text-muted fw-normal">Salina</dt>
                    <dd class="col-7 text-end"><?= htmlspecialchars($detalhe['salina']) ?></dd>
                    <dt class="col-5 text-muted fw-normal">Área</dt>
                    <dd class="col-7 text-end"><?= number_format($detalhe['area_m2'], 2, ',', ' ') ?> m²</dd>
                    <dt class="col-5 text-muted fw-normal">Capacidade</dt>
                    <dd class="col-7 text-end"><?= number_format($detalhe['capacidade_maxima_kg'], 2, ',', ' ') ?> kg</dd>
                </dl>
                <?php if ($podeGerir): ?>
                    <a href="index.php?page=tanques&id=<?= $detalhe['id_tanque'] ?>" class="btn btn-primary"><i class="bi bi-pencil"></i> Editar</a>
                <?php endif; ?>
                <a href="index.php?page=tanques&search=<?= urlencode($search) ?>&estado=<?= urlencode($estado) ?>" class="btn btn-outline-secondary">Fechar</a>
            </div>
        </div>
        <?php elseif (!$podeGerir): ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body text-muted text-center py-5">
                <i class="bi bi-eye fs-2 d-block mb-2"></i>
                Selecione um tanque para ver o detalhe.
            </div>
        </div>
        <?php else: ?>
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">
                <?= $tanque['id_tanque'] ? 'Editar tanque' : 'Novo tanque' ?>
            </div>
            <form method="post" action="index.php?page=tanques&id=<?= $tanque['id_tanque'] ?>" class="card-body">
                <div class="mb-3">
                    <label for="id_salina" class="form-label">Salina *</label>
                    <select id="id_salina" name="id_salina" class="form-select <?= isset($erros['id_salina']) ? 'is-invalid' : '' ?>">
                        <option value="">Selecione...</option>
                        <?php foreach ($salinas as $s): ?>
                            <option value="<?= $s['id_salina'] ?>" <?= $s['id_salina'] == $tanque['id_salina'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($s['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback"><?= $erros['id_salina'] ?? '' ?></div>
                </div>
                <div class="mb-3">
                    <label for="codigo" class="form-label">Código *</label>
                    <input type="text" id="codigo" name="codigo" maxlength="40" class="form-control <?= isset($erros['codigo']) ? 'is-invalid' : '' ?>"
                        value="<?= htmlspecialchars($tanque['codigo']) ?>">
                    <div class="invalid-feedback"><?= $erros['codigo'] ?? '' ?></div>
                </div>
                <div class="mb-3">
                    <label for="area_m2" class="form-label">Área (m²) *</label>
                    <input type="number" step="0.01" min="0.01" id="area_m2" name="area_m2" class="form-control <?= isset($erros['area_m2']) ? 'is-invalid' : '' ?>"
                        value="<?= htmlspecialchars($tanque['area_m2']) ?>">
                    <div class="invalid-feedback"><?= $erros['area_m2'] ?? '' ?></div>
                </div>
                <div class="mb-3">
                    <label for="capacidade_maxima_kg" class="form-label">Capacidade máxima (kg) *</label>
                    <input type="number" step="0.01" min="0.01" id="capacidade_maxima_kg" name="capacidade_maxima_kg" class="form-control <?= isset($erros['capacidade_maxima_kg']) ? 'is-invalid' : '' ?>"
                        value="<?= htmlspecialchars($tanque['capacidade_maxima_kg']) ?>">
                    <div class="invalid-feedback"><?= $erros['capacidade_maxima_kg'] ?? '' ?></div>
                </div>
                <div class="mb-3">
                    <label for="estado" class="form-label">Estado *</label>
                    <select id="estado" name="estado" class="form-select">
                        <?php foreach (['Ativo', 'Inativo', 'Manutenção'] as $e): ?>
                            <option <?= $tanque['estado'] === $e ? 'selected' : '' ?>><?= $e ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button class="btn btn-primary">Guardar</button>
                <a href="index.php?page=tanques" class="btn btn-outline-secondary">Cancelar</a>
            </form>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require 'app/views/layout/footer.php'; ?>