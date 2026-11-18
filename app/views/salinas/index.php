<?php
$titulo = 'Gestão de Dados Principais';
require 'app/views/layout/header.php';
?>

<?php require 'app/views/layout/tab_dados.php'; ?>

<div class="btn-group mb-3">
    <a href="index.php?page=salinas" class="btn btn-primary">Salinas</a>
    <a href="index.php?page=tanques" class="btn btn-outline-primary">Tanques</a>
</div>

<div class="row g-4">
    <div class="<?= $podeGerir ? 'col-lg-8' : 'col-12' ?>">
        <div class="card border-0 shadow-sm">
            <form method="get" class="card-body d-flex flex-wrap gap-2">
                <input type="hidden" name="page" value="salinas">
                <div class="input-group" style="max-width: 260px;">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Nome ou localização..." value="<?= htmlspecialchars($search) ?>">
                </div>
                <select name="estado" class="form-select w-auto" onchange="this.form.submit()">
                    <option value="">Todos os estados</option>
                    <?php foreach (['Ativa', 'Inativa', 'Manutenção'] as $e): ?>
                        <option <?= $estado === $e ? 'selected' : '' ?>><?= $e ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if ($podeGerir): ?>
                    <a href="index.php?page=salinas" class="btn btn-primary ms-auto"><i class="bi bi-plus-lg"></i> Nova salina</a>
                <?php endif; ?>
            </form>
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Localização</th>
                        <th class="text-end">Área (ha)</th>
                        <th>Estado</th>
                        <?php if ($podeGerir): ?><th>Ações</th><?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!$salinas): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Nenhuma salina encontrada.</td>
                        </tr>
                    <?php endif; ?>

                    <?php foreach ($salinas as $s): ?>
                        <tr>
                            <td><?= $s['id_salina'] ?></td>
                            <td><?= htmlspecialchars($s['nome']) ?></td>
                            <td><?= htmlspecialchars($s['localizacao']) ?></td>
                            <td class="text-end"><?= number_format($s['area_hectares'], 2, ',', ' ') ?></td>
                            <td><span class="badge text-bg-<?= $corEstado[$s['estado']] ?>"><?= $s['estado'] ?></span></td>
                            <?php if ($podeGerir): ?>
                            <td class="text-nowrap">
                                <a href="index.php?page=salinas&id=<?= $s['id_salina'] ?>" class="btn btn-sm btn-outline-primary" title="Editar"><i class="bi bi-pencil"></i></a>
                                <form method="post" action="index.php?page=salinas&action=delete" class="d-inline">
                                    <input type="hidden" name="id" value="<?= $s['id_salina'] ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar" onclick="return confirm('Tem a certeza que deseja eliminar esta salina?')"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="card-footer bg-white text-muted small">A mostrar <?= count($salinas) ?> registos</div>
        </div>
    </div>
    <?php if ($podeGerir): ?>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">
                <?= $salina['id_salina'] ? 'Editar salina' : 'Nova salina' ?>
            </div>
            <form method="post" action="index.php?page=salinas&id=<?= $salina['id_salina'] ?>" class="card-body">
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome *</label>
                    <input type="text" id="nome" name="nome" maxlength="150" class="form-control <?= isset($erros['nome']) ? 'is-invalid' : '' ?>"
                        value="<?= htmlspecialchars($salina['nome']) ?>">
                    <div class="invalid-feedback"><?= $erros['nome'] ?? '' ?></div>
                </div>
                <div class="mb-3">
                    <label for="localizacao" class="form-label">Localização *</label>
                    <input type="text" id="localizacao" name="localizacao" maxlength="180" class="form-control <?= isset($erros['localizacao']) ? 'is-invalid' : '' ?>"
                        value="<?= htmlspecialchars($salina['localizacao']) ?>">
                    <div class="invalid-feedback"><?= $erros['localizacao'] ?? '' ?></div>
                </div>
                <div class="mb-3">
                    <label for="area_hectares" class="form-label">Área (hectares) *</label>
                    <input type="number" step="0.01" min="0.01" id="area_hectares" name="area_hectares" class="form-control <?= isset($erros['area_hectares']) ? 'is-invalid' : '' ?>"
                        value="<?= htmlspecialchars($salina['area_hectares']) ?>">
                    <div class="invalid-feedback"><?= $erros['area_hectares'] ?? '' ?></div>
                </div>
                <div class="mb-3">
                    <label for="descricao" class="form-label">Descrição</label>
                    <textarea id="descricao" name="descricao" rows="3" maxlength="500" class="form-control"><?= htmlspecialchars($salina['descricao'] ?? '') ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="estado" class="form-label">Estado *</label>
                    <select id="estado" name="estado" class="form-select">
                        <?php foreach (['Ativa', 'Inativa', 'Manutenção'] as $e): ?>
                            <option <?= $salina['estado'] === $e ? 'selected' : '' ?>><?= $e ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button class="btn btn-primary">Guardar</button>
                <a href="index.php?page=salinas" class="btn btn-outline-secondary">Cancelar</a>
            </form>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php require 'app/views/layout/footer.php'; ?>
