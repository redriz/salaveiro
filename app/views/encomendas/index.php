<?php
$titulo = 'Gestão Comercial';
require 'app/views/layout/header.php';

$podeAlterar = $encomenda && !in_array($encomenda['estado'], ['Concluída', 'Cancelada']);
?>

<?php require 'app/views/layout/tab_comercial.php'; ?>

<!-- Contadores por estado -->
<div class="row g-3 mb-4">
    <?php foreach ($estados as $e): ?>
        <div class="col-6 col-md-4 col-xl-2">
            <a href="index.php?page=encomendas&estado=<?= urlencode($e) ?>" class="card border-0 shadow-sm text-center text-decoration-none h-100">
                <div class="card-body py-3">
                    <div class="fs-3 fw-bold text-body"><?= $contagem[$e] ?? 0 ?></div>
                    <span class="badge text-bg-<?= $corEstado[$e] ?>"><?= $e ?></span>
                </div>
            </a>
        </div>
    <?php endforeach; ?>
</div>

<div class="row g-4">

    <!-- Lista -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <form method="get" class="card-body d-flex flex-wrap gap-2">
                <input type="hidden" name="page" value="encomendas">
                <div class="input-group" style="max-width: 240px;">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Cliente ou nº" value="<?= htmlspecialchars($search) ?>">
                </div>
                <select name="estado" class="form-select w-auto" onchange="this.form.submit()">
                    <option value="">Todos os estados</option>
                    <?php foreach ($estados as $e): ?>
                        <option <?= $estado === $e ? 'selected' : '' ?>><?= $e ?></option>
                    <?php endforeach; ?>
                </select>
                <a href="index.php?page=encomendas" class="btn btn-primary ms-auto"><i class="bi bi-plus-lg"></i> Nova encomenda</a>
            </form>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nº</th>
                            <th>Data</th>
                            <th>Cliente</th>
                            <th>Produtos</th>
                            <th class="text-end">Total</th>
                            <th>Estado</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!$encomendas): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Nenhuma encomenda encontrada.</td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach ($encomendas as $e): ?>
                            <tr class="<?= $encomenda && $encomenda['id_encomenda'] == $e['id_encomenda'] ? 'table-active' : '' ?>">
                                <td>#<?= $e['id_encomenda'] ?></td>
                                <td class="text-nowrap"><?= date('d/m/Y', strtotime($e['data_encomenda'])) ?></td>
                                <td><?= htmlspecialchars($e['cliente']) ?></td>
                                <td class="small"><?= htmlspecialchars($e['produtos'] ?? '—') ?></td>
                                <td class="text-end text-nowrap"><?= number_format($e['valor_total'], 2, ',', ' ') ?> €</td>
                                <td><span class="badge text-bg-<?= $corEstado[$e['estado']] ?>"><?= $e['estado'] ?></span></td>
                                <td>
                                    <a href="index.php?page=encomendas&id=<?= $e['id_encomenda'] ?>&search=<?= urlencode($search) ?>&estado=<?= urlencode($estado) ?>"
                                        class="btn btn-sm btn-outline-secondary" title="Ver detalhe"><i class="bi bi-eye"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white text-muted small">A mostrar <?= count($encomendas) ?> registos</div>
        </div>
    </div>

    <!-- Painel lateral -->
    <div class="col-lg-4">
        <?php if ($encomenda): ?>

            <!-- Detalhe da encomenda -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center">
                    <span>Encomenda #<?= $encomenda['id_encomenda'] ?></span>
                    <span class="badge text-bg-<?= $corEstado[$encomenda['estado']] ?>"><?= $encomenda['estado'] ?></span>
                </div>
                <div class="card-body">
                    <dl class="row small mb-3">
                        <dt class="col-5 text-muted fw-normal">Cliente</dt>
                        <dd class="col-7 text-end"><?= htmlspecialchars($encomenda['cliente']) ?></dd>
                        <dt class="col-5 text-muted fw-normal">NIF</dt>
                        <dd class="col-7 text-end"><?= $encomenda['nif'] ?></dd>
                        <dt class="col-5 text-muted fw-normal">Morada</dt>
                        <dd class="col-7 text-end"><?= htmlspecialchars($encomenda['morada']) ?></dd>
                        <dt class="col-5 text-muted fw-normal">Data</dt>
                        <dd class="col-7 text-end"><?= date('d/m/Y H:i', strtotime($encomenda['data_encomenda'])) ?></dd>
                        <dt class="col-5 text-muted fw-normal">Registada por</dt>
                        <dd class="col-7 text-end"><?= htmlspecialchars($encomenda['registada_por']) ?></dd>
                        <?php if ($encomenda['observacoes']): ?>
                            <dt class="col-5 text-muted fw-normal">Observações</dt>
                            <dd class="col-7 text-end"><?= htmlspecialchars($encomenda['observacoes']) ?></dd>
                        <?php endif; ?>
                    </dl>

                    <table class="table table-sm small mb-3">
                        <thead>
                            <tr>
                                <th>Produto</th>
                                <th class="text-end">Qtd</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($itens as $i): ?>
                                <tr>
                                    <td><?= htmlspecialchars($i['nome']) ?>
                                        <div class="text-muted"><?= number_format($i['preco_unitario'], 2, ',', ' ') ?> € / un.</div>
                                    </td>
                                    <td class="text-end"><?= $i['quantidade'] ?></td>
                                    <td class="text-end text-nowrap"><?= number_format($i['subtotal'], 2, ',', ' ') ?> €</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2">Total</th>
                                <th class="text-end text-nowrap"><?= number_format($encomenda['valor_total'], 2, ',', ' ') ?> €</th>
                            </tr>
                        </tfoot>
                    </table>

                    <?php if ($encomenda['estado'] === 'Pendente'): ?>
                        <!-- Adicionar produto -->
                        <form method="post" action="index.php?page=encomendas&action=adicionarItem" class="d-flex gap-2 mb-3">
                            <input type="hidden" name="id" value="<?= $encomenda['id_encomenda'] ?>">
                            <select name="id_produto" class="form-select form-select-sm" aria-label="Produto" required>
                                <option value="">Adicionar produto...</option>
                                <?php foreach ($produtos as $p): ?>
                                    <option value="<?= $p['id_produto'] ?>"><?= htmlspecialchars($p['nome']) ?> (<?= $p['stock_unidades'] ?>)</option>
                                <?php endforeach; ?>
                            </select>
                            <input type="number" name="quantidade" min="1" value="1" class="form-control form-control-sm" style="width: 80px;" aria-label="Quantidade" required>
                            <button class="btn btn-sm btn-outline-primary" title="Adicionar"><i class="bi bi-plus-lg"></i></button>
                        </form>
                    <?php endif; ?>

                    <?php if ($podeAlterar): ?>
                        <!-- Alterar estado -->
                        <form method="post" action="index.php?page=encomendas&action=estado" class="mb-2">
                            <input type="hidden" name="id" value="<?= $encomenda['id_encomenda'] ?>">
                            <label for="estado" class="form-label small">Alterar estado</label>
                            <select id="estado" name="estado" class="form-select mb-2">
                                <?php foreach ($estados as $e): ?>
                                    <?php if ($e !== 'Cancelada'): ?>
                                        <option <?= $encomenda['estado'] === $e ? 'selected' : '' ?>><?= $e ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                            <button class="btn btn-primary">Atualizar</button>
                        </form>

                        <!-- Cancelar -->
                        <form method="post" action="index.php?page=encomendas&action=cancelar"
                            onsubmit="return confirm('Cancelar a encomenda #<?= $encomenda['id_encomenda'] ?>? O stock dos produtos será reposto.')">
                            <input type="hidden" name="id" value="<?= $encomenda['id_encomenda'] ?>">
                            <button class="btn btn-outline-danger w-100"><i class="bi bi-x-circle"></i> Cancelar encomenda</button>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-secondary small mb-0">Encomenda <?= mb_strtolower($encomenda['estado']) ?>: já não pode ser alterada.</div>
                    <?php endif; ?>
                </div>
            </div>

        <?php else: ?>

            <!-- Nova encomenda -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">Nova encomenda</div>
                <form method="post" action="index.php?page=encomendas" class="card-body">
                    <div class="mb-3">
                        <label for="id_cliente" class="form-label">Cliente *</label>
                        <select id="id_cliente" name="id_cliente" class="form-select <?= isset($erros['id_cliente']) ? 'is-invalid' : '' ?>">
                            <option value="">Selecione...</option>
                            <?php foreach ($clientes as $c): ?>
                                <option value="<?= $c['id_cliente'] ?>" <?= $c['id_cliente'] == $nova['id_cliente'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($c['nome']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback"><?= $erros['id_cliente'] ?? '' ?></div>
                    </div>
                    <div class="mb-3">
                        <label for="id_produto" class="form-label">Produto *</label>
                        <select id="id_produto" name="id_produto" class="form-select <?= isset($erros['id_produto']) ? 'is-invalid' : '' ?>">
                            <option value="">Selecione...</option>
                            <?php foreach ($produtos as $p): ?>
                                <option value="<?= $p['id_produto'] ?>" <?= $p['id_produto'] == $nova['id_produto'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($p['nome']) ?> · <?= number_format($p['preco_venda'], 2, ',', ' ') ?> € (stock: <?= $p['stock_unidades'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback"><?= $erros['id_produto'] ?? '' ?></div>
                    </div>
                    <div class="mb-3">
                        <label for="quantidade" class="form-label">Quantidade *</label>
                        <input type="number" id="quantidade" name="quantidade" min="1" step="1"
                            class="form-control <?= isset($erros['quantidade']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($nova['quantidade']) ?>">
                        <div class="invalid-feedback"><?= $erros['quantidade'] ?? '' ?></div>
                    </div>
                    <div class="mb-3">
                        <label for="observacoes" class="form-label">Observações</label>
                        <textarea id="observacoes" name="observacoes" rows="2" maxlength="500" class="form-control"><?= htmlspecialchars($nova['observacoes']) ?></textarea>
                    </div>
                    <small class="text-muted d-block mb-3">Depois de criar, pode adicionar mais produtos enquanto estiver pendente.</small>
                    <button class="btn btn-primary">Criar encomenda</button>
                </form>
            </div>

        <?php endif; ?>
    </div>
</div>

<?php require 'app/views/layout/footer.php'; ?>