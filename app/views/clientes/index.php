<?php
$titulo = 'Gestão Comercial';
require 'app/views/layout/header.php';
?>

<?php require 'app/views/layout/tab_comercial.php'; ?>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <form method="get" class="card-body d-flex flex-wrap gap-2">
                <input type="hidden" name="page" value="clientes">
                <div class="input-group" style="max-width: 260px;">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Nome ou NIF..." value="<?= htmlspecialchars($search) ?>">
                </div>
                <select name="estado" class="form-select w-auto" onchange="this.form.submit()">
                    <option value="">Todos os estados</option>
                    <?php foreach (['Ativo', 'Inativo'] as $e): ?>
                        <option <?= $estado === $e ? 'selected' : '' ?>><?= $e ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if ($podeGerir): ?>
                    <a href="index.php?page=clientes" class="btn btn-primary ms-auto"><i class="bi bi-plus-lg"></i> Novo cliente</a>
                <?php endif; ?>
            </form>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 table-acoes">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>NIF</th>
                            <th>Telefone</th>
                            <th>Morada</th>
                            <th>Estado</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!$clientes): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Nenhum cliente encontrado.</td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach ($clientes as $c): ?>
                            <tr class="<?= $detalhe && $detalhe['id_cliente'] == $c['id_cliente'] ? 'table-active' : '' ?>">
                                <td><?= $c['id_cliente'] ?></td>
                                <td>
                                    <?= htmlspecialchars($c['nome']) ?>
                                    <div class="small text-muted"><?= htmlspecialchars($c['email']) ?></div>
                                </td>
                                <td><?= $c['nif'] ?></td>
                                <td><?= htmlspecialchars($c['telefone']) ?></td>
                                <td><?= htmlspecialchars($c['morada']) ?></td>
                                <td><span class="badge text-bg-<?= $corEstado[$c['estado']] ?>"><?= $c['estado'] ?></span></td>
                                <td class="text-nowrap">
                                    <a href="index.php?page=clientes&ver=<?= $c['id_cliente'] ?>&search=<?= urlencode($search) ?>&estado=<?= urlencode($estado) ?>" class="btn btn-sm btn-outline-secondary" title="Ver detalhe"><i class="bi bi-eye"></i></a>
                                    <?php if ($podeGerir): ?>
                                    <a href="index.php?page=clientes&id=<?= $c['id_cliente'] ?>" class="btn btn-sm btn-outline-primary" title="Editar"><i class="bi bi-pencil"></i></a>
                                    <form method="post" action="index.php?page=clientes&action=delete" class="d-inline">
                                        <input type="hidden" name="id" value="<?= $c['id_cliente'] ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar" data-confirmar="Eliminar este cliente?" data-confirmar-texto="Esta ação não pode ser desfeita." data-confirmar-botao="Eliminar"><i class="bi bi-trash"></i></button>
                                    </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white text-muted small">A mostrar <?= count($clientes) ?> registos</div>
        </div>
    </div>

    <div class="col-lg-4">
        <?php if ($detalhe): ?>
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center">
                <span><?= htmlspecialchars($detalhe['nome']) ?></span>
                <span class="badge text-bg-<?= $corEstado[$detalhe['estado']] ?>"><?= $detalhe['estado'] ?></span>
            </div>
            <div class="card-body">
                <dl class="row small mb-3">
                    <dt class="col-5 text-muted fw-normal">ID</dt>
                    <dd class="col-7 text-end"><?= $detalhe['id_cliente'] ?></dd>
                    <dt class="col-5 text-muted fw-normal">NIF</dt>
                    <dd class="col-7 text-end"><?= $detalhe['nif'] ?></dd>
                    <dt class="col-5 text-muted fw-normal">Telefone</dt>
                    <dd class="col-7 text-end"><?= htmlspecialchars($detalhe['telefone']) ?></dd>
                    <dt class="col-5 text-muted fw-normal">Email</dt>
                    <dd class="col-7 text-end"><?= htmlspecialchars($detalhe['email']) ?></dd>
                    <dt class="col-5 text-muted fw-normal">Morada</dt>
                    <dd class="col-7 text-end"><?= htmlspecialchars($detalhe['morada']) ?></dd>
                </dl>
                <?php if ($podeGerir): ?>
                    <a href="index.php?page=clientes&id=<?= $detalhe['id_cliente'] ?>" class="btn btn-primary"><i class="bi bi-pencil"></i> Editar</a>
                <?php endif; ?>
                <a href="index.php?page=clientes&search=<?= urlencode($search) ?>&estado=<?= urlencode($estado) ?>" class="btn btn-outline-secondary">Fechar</a>
            </div>
        </div>
        <?php elseif (!$podeGerir): ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body text-muted text-center py-5">
                <i class="bi bi-eye fs-2 d-block mb-2"></i>
                Selecione um cliente para ver o detalhe.
            </div>
        </div>
        <?php else: ?>
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">
                <?= $cliente['id_cliente'] ? 'Editar cliente' : 'Novo cliente' ?>
            </div>
            <form method="post" action="index.php?page=clientes&id=<?= $cliente['id_cliente'] ?>" class="card-body">
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome *</label>
                    <input type="text" id="nome" name="nome" maxlength="150" class="form-control <?= isset($erros['nome']) ? 'is-invalid' : '' ?>"
                        value="<?= htmlspecialchars($cliente['nome']) ?>">
                    <div class="invalid-feedback"><?= $erros['nome'] ?? '' ?></div>
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <label for="nif" class="form-label">NIF *</label>
                        <input type="text" id="nif" name="nif" maxlength="9" inputmode="numeric" class="form-control <?= isset($erros['nif']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($cliente['nif']) ?>">
                        <div class="invalid-feedback"><?= $erros['nif'] ?? '' ?></div>
                    </div>
                    <div class="col-6 mb-3">
                        <label for="telefone" class="form-label">Telefone *</label>
                        <input type="tel" id="telefone" name="telefone" maxlength="9" class="form-control <?= isset($erros['telefone']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($cliente['telefone']) ?>">
                        <div class="invalid-feedback"><?= $erros['telefone'] ?? '' ?></div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email *</label>
                    <input type="email" id="email" name="email" maxlength="180" class="form-control <?= isset($erros['email']) ? 'is-invalid' : '' ?>"
                        value="<?= htmlspecialchars($cliente['email']) ?>">
                    <div class="invalid-feedback"><?= $erros['email'] ?? '' ?></div>
                </div>
                <div class="mb-3">
                    <label for="morada" class="form-label">Morada *</label>
                    <input type="text" id="morada" name="morada" maxlength="255" class="form-control <?= isset($erros['morada']) ? 'is-invalid' : '' ?>"
                        value="<?= htmlspecialchars($cliente['morada']) ?>">
                    <div class="invalid-feedback"><?= $erros['morada'] ?? '' ?></div>
                </div>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="estado" name="estado" <?= $cliente['estado'] === 'Ativo' ? 'checked' : '' ?>>
                    <label class="form-check-label" for="estado">Ativo</label>
                </div>

                <button class="btn btn-primary">Guardar</button>
                <a href="index.php?page=clientes" class="btn btn-outline-secondary">Cancelar</a>
            </form>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require 'app/views/layout/footer.php'; ?>