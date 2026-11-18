<?php
$titulo = 'Gestão de Dados Principais';
require 'app/views/layout/header.php';
?>

<?php require 'app/views/layout/tab_dados.php'; ?>

<div class="btn-group mb-3">
    <a href="index.php?page=categorias" class="btn btn-primary">Categorias</a>
    <a href="index.php?page=produtos" class="btn btn-outline-primary">Produtos</a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <form method="get" class="card-body d-flex flex-wrap gap-2">
                <input type="hidden" name="page" value="categorias">
                <div class="input-group" style="max-width: 260px;">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Pesquisar..." value="<?= htmlspecialchars($search) ?>">
                </div>
                <a href="index.php?page=categorias" class="btn btn-primary ms-auto"><i class="bi bi-plus-lg"></i> Nova categoria</a>
            </form>
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Descrição</th>
                        <th class="text-end">Produtos</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!$categorias): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Nenhuma categoria encontrada.</td>
                        </tr>
                    <?php endif; ?>

                    <?php foreach ($categorias as $c): ?>
                        <tr>
                            <td><?= $c['id_categoria'] ?></td>
                            <td><?= htmlspecialchars($c['nome']) ?></td>
                            <td class="text-muted small"><?= htmlspecialchars($c['descricao'] ?? '—') ?></td>
                            <td class="text-end"><?= $c['total_produtos'] ?></td>
                            <td class="text-nowrap">
                                <a href="index.php?page=categorias&id=<?= $c['id_categoria'] ?>" class="btn btn-sm btn-outline-primary" title="Editar"><i class="bi bi-pencil"></i></a>
                                <form method="post" action="index.php?page=categorias&action=delete" class="d-inline">
                                    <input type="hidden" name="id" value="<?= $c['id_categoria'] ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar" onclick="return confirm('Tem a certeza que deseja eliminar esta categoria?')"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="card-footer bg-white text-muted small">A mostrar <?= count($categorias) ?> registos</div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">
                <?= $categoria['id_categoria'] ? 'Editar categoria' : 'Nova categoria' ?>
            </div>
            <form method="post" action="index.php?page=categorias&id=<?= $categoria['id_categoria'] ?>" class="card-body">
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome *</label>
                    <input type="text" id="nome" name="nome" maxlength="100" class="form-control <?= isset($erros['nome']) ? 'is-invalid' : '' ?>"
                        value="<?= htmlspecialchars($categoria['nome']) ?>">
                    <div class="invalid-feedback"><?= $erros['nome'] ?? '' ?></div>
                </div>
                <div class="mb-3">
                    <label for="descricao" class="form-label">Descrição</label>
                    <textarea id="descricao" name="descricao" rows="3" maxlength="255" class="form-control"><?= htmlspecialchars($categoria['descricao'] ?? '') ?></textarea>
                </div>

                <button class="btn btn-primary">Guardar</button>
                <a href="index.php?page=categorias" class="btn btn-outline-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>

<?php require 'app/views/layout/footer.php'; ?>
