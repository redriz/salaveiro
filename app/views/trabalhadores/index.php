<?php
$titulo = 'Gestão de Dados Principais';
require 'app/views/layout/header.php';
?>

<?php require 'app/views/layout/tab_dados.php'; ?>

<div class="row g-4">
    <div class="<?= $podeGerir ? 'col-lg-8' : 'col-12' ?>">
        <div class="card border-0 shadow-sm">
            <form method="get" class="card-body d-flex flex-wrap gap-2">
                <input type="hidden" name="page" value="trabalhadores">
                <div class="input-group" style="max-width: 260px;">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Pesquisar..." value="<?= htmlspecialchars($search) ?>">
                </div>
                <select name="estado" class="form-select w-auto" onchange="this.form.submit()">
                    <option value="">Todos os estados</option>
                    <option value="Ativo" <?= $estado === 'Ativo' ? 'selected' : '' ?>>Ativo</option>
                    <option value="Inativo" <?= $estado === 'Inativo' ? 'selected' : '' ?>>Inativo</option>
                </select>
                <?php if ($podeGerir): ?>
                    <a href="index.php?page=trabalhadores" class="btn btn-primary ms-auto"><i class="bi bi-plus-lg"></i> Novo trabalhador</a>
                <?php endif; ?>
            </form>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0<?= $podeGerir ? ' table-acoes' : '' ?>">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Função</th>
                            <th>Salina</th>
                            <th>Telefone</th>
                            <th>Email</th>
                            <th>Estado</th>
                            <?php if ($podeGerir): ?><th>Ações</th><?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!$trabalhadores): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">Nenhum trabalhador encontrado.</td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach ($trabalhadores as $t): ?>
                            <tr>
                                <td><?= $t['id_trabalhador'] ?></td>
                                <td><?= htmlspecialchars($t['nome']) ?></td>
                                <td><?= htmlspecialchars($t['funcao']) ?></td>
                                <td><?= htmlspecialchars($t['salina'] ?? '—') ?></td>
                                <td><?= htmlspecialchars($t['telefone'] ?? '—') ?></td>
                                <td><?= htmlspecialchars($t['email'] ?: '—') ?></td>
                                <td><span class="badge text-bg-<?= $t['estado'] === 'Ativo' ? 'success' : 'danger' ?>"><?= $t['estado'] ?></span></td>
                                <?php if ($podeGerir): ?>
                                <td class="text-nowrap">
                                    <a href="index.php?page=trabalhadores&id=<?= $t['id_trabalhador'] ?>" class="btn btn-sm btn-outline-primary" title="Editar"><i class="bi bi-pencil"></i></a>
                                    <form method="post" action="index.php?page=trabalhadores&action=delete" class="d-inline">
                                        <input type="hidden" name="id" value="<?= $t['id_trabalhador'] ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar" data-confirmar="Eliminar este trabalhador?" data-confirmar-texto="Esta ação não pode ser desfeita." data-confirmar-botao="Eliminar"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white text-muted small">A mostrar <?= count($trabalhadores) ?> registos</div>
        </div>
    </div>
    <?php if ($podeGerir): ?>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">
                <?= $trabalhador['id_trabalhador'] ? 'Editar trabalhador' : 'Novo trabalhador' ?>
            </div>
            <form method="post" action="index.php?page=trabalhadores&id=<?= $trabalhador['id_trabalhador'] ?>" class="card-body">
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome *</label>
                    <input type="text" id="nome" name="nome" class="form-control <?= isset($erros['nome']) ? 'is-invalid' : '' ?>"
                        value="<?= htmlspecialchars($trabalhador['nome']) ?>">
                    <div class="invalid-feedback"><?= $erros['nome'] ?? '' ?></div>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email *</label>
                    <input type="email" id="email" name="email" class="form-control <?= isset($erros['email']) ? 'is-invalid' : '' ?>"
                        value="<?= htmlspecialchars($trabalhador['email']) ?>">
                    <div class="invalid-feedback"><?= $erros['email'] ?? '' ?></div>
                </div>
                <div class="mb-3">
                    <label for="telefone" class="form-label">Telefone *</label>
                    <input type="tel" maxlength="9" id="telefone" name="telefone" class="form-control <?= isset($erros['telefone']) ? 'is-invalid' : '' ?>"
                        value="<?= htmlspecialchars($trabalhador['telefone']) ?>">
                    <div class="invalid-feedback"><?= $erros['telefone'] ?? '' ?></div>
                </div>
                <div class="mb-3">
                    <label for="data_admissao" class="form-label">Data de Admissão *</label>
                    <input type="date" id="data_admissao" name="data_admissao" max="<?= date('Y-m-d') ?>" class="form-control <?= isset($erros['data_admissao']) ? 'is-invalid' : '' ?>"
                        value="<?= $trabalhador['data_admissao'] ?>">
                    <div class="invalid-feedback"><?= $erros['data_admissao'] ?? '' ?></div>
                </div>
                <div class="mb-3">
                    <label for="id_salina" class="form-label">Salina</label>
                    <select id="id_salina" name="id_salina" class="form-select <?= isset($erros['id_salina']) ? 'is-invalid' : '' ?>">
                        <option value="">— Nenhum —</option>
                        <?php foreach ($salinas as $s): ?>
                            <option value="<?= $s['id_salina'] ?>" <?= $s['id_salina'] == $trabalhador['id_salina'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($s['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="id_funcao" class="form-label">Função *</label>
                    <select id="id_funcao" name="id_funcao" class="form-select <?= isset($erros['id_funcao']) ? 'is-invalid' : '' ?>">
                        <option value="">— Nenhum —</option>
                        <?php foreach ($funcoes as $f): ?>
                            <option value="<?= $f['id_funcao'] ?>" <?= $f['id_funcao'] == $trabalhador['id_funcao'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($f['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="estado" name="estado" <?= $trabalhador['estado'] === 'Ativo' ? 'checked' : '' ?>>
                    <label class="form-check-label" for="estado">Ativo</label>
                </div>

                <button class="btn btn-primary">Guardar</button>
                <a href="index.php?page=trabalhadores" class="btn btn-outline-secondary">Cancelar</a>
            </form>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php require 'app/views/layout/footer.php'; ?>