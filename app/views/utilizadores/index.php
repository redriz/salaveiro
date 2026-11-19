<?php
$titulo = 'Gestão de Dados Principais';
require 'app/views/layout/header.php';
?>

<?php require 'app/views/layout/tab_dados.php'; ?>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <form method="get" class="card-body d-flex flex-wrap gap-2">
                <input type="hidden" name="page" value="utilizadores">
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
                    <a href="index.php?page=utilizadores" class="btn btn-primary ms-auto"><i class="bi bi-plus-lg"></i> Novo utilizador</a>
                <?php endif; ?>
            </form>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 table-acoes">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Trabalhador</th>
                            <th>Perfil</th>
                            <th>Estado</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!$utilizadores): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">Nenhum utilizador encontrado.</td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach ($utilizadores as $u): ?>
                            <tr class="<?= $detalhe && $detalhe['id_utilizador'] == $u['id_utilizador'] ? 'table-active' : '' ?>">
                                <td><?= $u['id_utilizador'] ?></td>
                                <td><?= htmlspecialchars($u['username']) ?></td>
                                <td><?= htmlspecialchars($u['nome']) ?></td>
                                <td><?= htmlspecialchars($u['email']) ?></td>
                                <td><?= htmlspecialchars($u['trabalhador'] ?? '—') ?></td>
                                <td><span class="badge text-bg-<?= $corPerfil[$u['perfil']] ?>"><?= $u['perfil'] ?></span></td>
                                <td><span class="badge text-bg-<?= $u['estado'] === 'Ativo' ? 'success' : 'danger' ?>"><?= $u['estado'] ?></span></td>
                                <td class="text-nowrap">
                                    <a href="index.php?page=utilizadores&ver=<?= $u['id_utilizador'] ?>&search=<?= urlencode($search) ?>&estado=<?= urlencode($estado) ?>" class="btn btn-sm btn-outline-secondary" title="Ver detalhe"><i class="bi bi-eye"></i></a>
                                    <?php if ($podeGerir): ?>
                                    <a href="index.php?page=utilizadores&id=<?= $u['id_utilizador'] ?>" class="btn btn-sm btn-outline-primary" title="Editar"><i class="bi bi-pencil"></i></a>
                                    <form method="post" action="index.php?page=utilizadores&action=delete" class="d-inline">
                                        <input type="hidden" name="id" value="<?= $u['id_utilizador'] ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar" data-confirmar="Eliminar este utilizador?" data-confirmar-texto="Esta ação não pode ser desfeita." data-confirmar-botao="Eliminar"><i class="bi bi-trash"></i></button>
                                    </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white text-muted small">A mostrar <?= count($utilizadores) ?> registos</div>
        </div>
    </div>
    <div class="col-lg-4">
        <?php if ($detalhe): ?>
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center">
                <span><?= htmlspecialchars($detalhe['nome']) ?></span>
                <span class="badge text-bg-<?= $detalhe['estado'] === 'Ativo' ? 'success' : 'danger' ?>"><?= $detalhe['estado'] ?></span>
            </div>
            <div class="card-body">
                <dl class="row small mb-3">
                    <dt class="col-5 text-muted fw-normal">ID</dt>
                    <dd class="col-7 text-end"><?= $detalhe['id_utilizador'] ?></dd>
                    <dt class="col-5 text-muted fw-normal">Username</dt>
                    <dd class="col-7 text-end"><?= htmlspecialchars($detalhe['username']) ?></dd>
                    <dt class="col-5 text-muted fw-normal">Email</dt>
                    <dd class="col-7 text-end"><?= htmlspecialchars($detalhe['email']) ?></dd>
                    <dt class="col-5 text-muted fw-normal">Perfil</dt>
                    <dd class="col-7 text-end"><span class="badge text-bg-<?= $corPerfil[$detalhe['perfil']] ?? 'secondary' ?>"><?= $detalhe['perfil'] ?></span></dd>
                    <dt class="col-5 text-muted fw-normal">Trabalhador</dt>
                    <dd class="col-7 text-end"><?= htmlspecialchars($detalhe['trabalhador'] ?? '—') ?></dd>
                    <dt class="col-5 text-muted fw-normal">Último acesso</dt>
                    <dd class="col-7 text-end"><?= $detalhe['ultimo_acesso'] ? date('d/m/Y H:i', strtotime($detalhe['ultimo_acesso'])) : 'Nunca' ?></dd>
                    <dt class="col-5 text-muted fw-normal">Criado em</dt>
                    <dd class="col-7 text-end"><?= date('d/m/Y H:i', strtotime($detalhe['criado_em'])) ?></dd>
                </dl>
                <?php if ($podeGerir): ?>
                    <a href="index.php?page=utilizadores&id=<?= $detalhe['id_utilizador'] ?>" class="btn btn-primary"><i class="bi bi-pencil"></i> Editar</a>
                <?php endif; ?>
                <a href="index.php?page=utilizadores&search=<?= urlencode($search) ?>&estado=<?= urlencode($estado) ?>" class="btn btn-outline-secondary">Fechar</a>
            </div>
        </div>
        <?php elseif (!$podeGerir): ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body text-muted text-center py-5">
                <i class="bi bi-eye fs-2 d-block mb-2"></i>
                Selecione um utilizador para ver o detalhe.
            </div>
        </div>
        <?php else: ?>
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">
                <?= $utilizador['id_utilizador'] ? 'Editar utilizador' : 'Novo utilizador' ?>
            </div>
            <form method="post" action="index.php?page=utilizadores&id=<?= $utilizador['id_utilizador'] ?>" class="card-body">
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome *</label>
                    <input type="text" id="nome" name="nome" class="form-control <?= isset($erros['nome']) ? 'is-invalid' : '' ?>"
                        value="<?= htmlspecialchars($utilizador['nome']) ?>">
                    <div class="invalid-feedback"><?= $erros['nome'] ?? '' ?></div>
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">Username *</label>
                    <input type="text" id="username" name="username" class="form-control <?= isset($erros['username']) ? 'is-invalid' : '' ?>"
                        value="<?= htmlspecialchars($utilizador['username']) ?>">
                    <div class="invalid-feedback"><?= $erros['username'] ?? '' ?></div>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email *</label>
                    <input type="email" id="email" name="email" class="form-control <?= isset($erros['email']) ? 'is-invalid' : '' ?>"
                        value="<?= htmlspecialchars($utilizador['email']) ?>">
                    <div class="invalid-feedback"><?= $erros['email'] ?? '' ?></div>
                </div>
                <div class="mb-3">
                    <label for="palavra_passe" class="form-label">Palavra-passe *</label>
                    <input type="password" id="palavra_passe" name="palavra_passe" class="form-control <?= isset($erros['palavra_passe']) ? 'is-invalid' : '' ?>"
                        placeholder="<?= $utilizador['id_utilizador'] ? 'Deixar vazio para manter' : 'Mínimo 8 caracteres' ?>">
                    <div class="invalid-feedback"><?= $erros['palavra_passe'] ?? '' ?></div>
                </div>
                <div class="mb-3">
                    <label for="id_trabalhador" class="form-label">Trabalhador</label>
                    <select id="id_trabalhador" name="id_trabalhador" class="form-select <?= isset($erros['id_trabalhador']) ? 'is-invalid' : '' ?>">
                        <option value="">— Nenhum —</option>
                        <?php foreach ($trabalhadores as $t): ?>
                            <option value="<?= $t['id_trabalhador'] ?>" <?= $t['id_trabalhador'] == $utilizador['id_trabalhador'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($t['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback"><?= $erros['id_trabalhador'] ?? '' ?></div>
                </div>
                <div class="mb-3">
                    <label for="id_perfil" class="form-label">Perfil *</label>
                    <select id="id_perfil" name="id_perfil" class="form-select <?= isset($erros['id_perfil']) ? 'is-invalid' : '' ?>">
                        <option value="">Selecione...</option>
                        <?php foreach ($perfis as $p): ?>
                            <option value="<?= $p['id_perfil'] ?>" <?= $p['id_perfil'] == $utilizador['id_perfil'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($p['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback"><?= $erros['id_perfil'] ?? '' ?></div>
                </div>

                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="estado" name="estado" <?= $utilizador['estado'] === 'Ativo' ? 'checked' : '' ?>>
                    <label class="form-check-label" for="estado">Ativo</label>
                </div>

                <button class="btn btn-primary">Guardar</button>
                <a href="index.php?page=utilizadores" class="btn btn-outline-secondary">Cancelar</a>
            </form>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require 'app/views/layout/footer.php'; ?>