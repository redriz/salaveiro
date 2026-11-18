<?php
$titulo = 'Gestão de Dados Principais';
require 'app/views/layout/header.php';
?>

<?php require 'app/views/layout/tab_dados.php'; ?>

<div class="btn-group mb-3">
    <a href="index.php?page=categorias" class="btn btn-outline-primary">Categorias</a>
    <a href="index.php?page=produtos" class="btn btn-primary">Produtos</a>
</div>

<div class="row g-4">
    <div class="<?= $podeGerir ? 'col-lg-8' : 'col-12' ?>">
        <div class="card border-0 shadow-sm">
            <form method="get" class="card-body d-flex flex-wrap gap-2">
                <input type="hidden" name="page" value="produtos">
                <div class="input-group" style="max-width: 260px;">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Produto ou categoria..." value="<?= htmlspecialchars($search) ?>">
                </div>
                <select name="estado" class="form-select w-auto" onchange="this.form.submit()">
                    <option value="">Todos os estados</option>
                    <?php foreach (['Ativo', 'Inativo'] as $e): ?>
                        <option <?= $estado === $e ? 'selected' : '' ?>><?= $e ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if ($podeGerir): ?>
                    <a href="index.php?page=produtos" class="btn btn-primary ms-auto"><i class="bi bi-plus-lg"></i> Novo produto</a>
                <?php endif; ?>
            </form>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Categoria</th>
                            <th class="text-end">Peso (kg)</th>
                            <th class="text-end">Preço</th>
                            <th class="text-end">Stock</th>
                            <th>Estado</th>
                            <?php if ($podeGerir): ?><th>Ações</th><?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!$produtos): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">Nenhum produto encontrado.</td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach ($produtos as $p): ?>
                            <tr>
                                <td><?= $p['id_produto'] ?></td>
                                <td><?= htmlspecialchars($p['nome']) ?></td>
                                <td><?= htmlspecialchars($p['categoria']) ?></td>
                                <td class="text-end"><?= number_format($p['peso_kg'], 3, ',', ' ') ?></td>
                                <td class="text-end text-nowrap"><?= number_format($p['preco_venda'], 2, ',', ' ') ?> €</td>
                                <td class="text-end"><?= $p['stock_unidades'] ?></td>
                                <td><span class="badge text-bg-<?= $corEstado[$p['estado']] ?>"><?= $p['estado'] ?></span></td>
                                <?php if ($podeGerir): ?>
                                <td class="text-nowrap">
                                    <a href="index.php?page=produtos&id=<?= $p['id_produto'] ?>" class="btn btn-sm btn-outline-primary" title="Editar"><i class="bi bi-pencil"></i></a>
                                    <form method="post" action="index.php?page=produtos&action=delete" class="d-inline">
                                        <input type="hidden" name="id" value="<?= $p['id_produto'] ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar" onclick="return confirm('Tem a certeza que deseja eliminar este produto?')"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white text-muted small">A mostrar <?= count($produtos) ?> registos</div>
        </div>
    </div>
    <?php if ($podeGerir): ?>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">
                <?= $produto['id_produto'] ? 'Editar produto' : 'Novo produto' ?>
            </div>
            <form method="post" action="index.php?page=produtos&id=<?= $produto['id_produto'] ?>" class="card-body">
                <div class="mb-3">
                    <label for="id_categoria" class="form-label">Categoria *</label>
                    <select id="id_categoria" name="id_categoria" class="form-select <?= isset($erros['id_categoria']) ? 'is-invalid' : '' ?>">
                        <option value="">Selecione...</option>
                        <?php foreach ($categorias as $c): ?>
                            <option value="<?= $c['id_categoria'] ?>" <?= $c['id_categoria'] == $produto['id_categoria'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($c['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback"><?= $erros['id_categoria'] ?? '' ?></div>
                </div>
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome *</label>
                    <input type="text" id="nome" name="nome" maxlength="150" class="form-control <?= isset($erros['nome']) ? 'is-invalid' : '' ?>"
                        value="<?= htmlspecialchars($produto['nome']) ?>">
                    <div class="invalid-feedback"><?= $erros['nome'] ?? '' ?></div>
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <label for="peso_kg" class="form-label">Peso (kg) *</label>
                        <input type="number" step="0.001" min="0.001" id="peso_kg" name="peso_kg" class="form-control <?= isset($erros['peso_kg']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($produto['peso_kg']) ?>">
                        <div class="invalid-feedback"><?= $erros['peso_kg'] ?? '' ?></div>
                    </div>
                    <div class="col-6 mb-3">
                        <label for="preco_venda" class="form-label">Preço (€) *</label>
                        <input type="number" step="0.01" min="0.01" id="preco_venda" name="preco_venda" class="form-control <?= isset($erros['preco_venda']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($produto['preco_venda']) ?>">
                        <div class="invalid-feedback"><?= $erros['preco_venda'] ?? '' ?></div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="stock_unidades" class="form-label">Stock (unidades) *</label>
                    <input type="number" step="1" min="0" id="stock_unidades" name="stock_unidades" class="form-control <?= isset($erros['stock_unidades']) ? 'is-invalid' : '' ?>"
                        value="<?= htmlspecialchars($produto['stock_unidades']) ?>">
                    <div class="invalid-feedback"><?= $erros['stock_unidades'] ?? '' ?></div>
                </div>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="estado" name="estado" <?= $produto['estado'] === 'Ativo' ? 'checked' : '' ?>>
                    <label class="form-check-label" for="estado">Ativo</label>
                </div>

                <button class="btn btn-primary">Guardar</button>
                <a href="index.php?page=produtos" class="btn btn-outline-secondary">Cancelar</a>
            </form>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php require 'app/views/layout/footer.php'; ?>
