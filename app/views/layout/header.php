<?php
$current = $_GET['page'] ?? 'dashboard';
if (in_array($current, ['utilizadores', 'trabalhadores', 'salinas', 'tanques', 'categorias', 'produtos'])) {
    $current = 'dados';
}

if (in_array($current, ['encomendas', 'clientes', 'stock'])) {
    $current = 'comercial';
}

$podeGerir = podeGerir($_GET['page'] ?? 'dashboard');

$menu = [
    'dashboard' => ['Dashboard', 'bi-speedometer2'],
    'dados'     => ['Dados Principais', 'bi-database'],
    'producao'  => ['Produção', 'bi-droplet-half'],
    'comercial' => ['Comercial', 'bi-cart3'],
    'externos'  => ['Dados Externos', 'bi-cloud-download']
];

$corPerfil = [
    'Administrador' => 'primary',
    'Operador'        => 'info',
    'Consultor'      => 'secondary',
];

$corEstado = [
    'Ativo' => 'success',
    'Ativa' => 'success',
    'Inativo' => 'secondary',
    'Inativa' => 'secondary',
    'Manutenção' => 'warning',
    'Pendente' => 'warning',
    'Confirmada' => 'secondary',
    'Em preparação' => 'info',
    'Expedida' => 'primary',
    'Concluída' => 'success',
    'Cancelada' => 'danger'
];

?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SalAveiro - Gestão</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <script>
        try {
            if (localStorage.getItem('sidebar') === 'recolhida') {
                document.documentElement.classList.add('sidebar-recolhida');
            }
        } catch (e) {}
    </script>
</head>

<body>
    <div class="d-flex min-vh-100">
        <aside class="sidebar d-flex flex-column" id="sidebar">
            <div class="sidebar-logo">
                <i class="bi bi-droplet-fill"></i><span class="sidebar-texto">SalAveiro</span>
            </div>

            <nav class="nav flex-column">
                <?php foreach ($menu as $key => $item): ?>
                    <a class="nav-link <?= $current === $key ? 'active' : '' ?>" href="index.php?page=<?= $key ?>" title="<?= $item[0] ?>">
                        <i class="bi <?= $item[1] ?>"></i><span class="sidebar-texto"><?= $item[0] ?></span>
                    </a>
                <?php endforeach; ?>
            </nav>

            <a class="nav-link mt-auto sidebar-sair" href="index.php?page=auth&action=logout" title="Sair">
                <i class="bi bi-box-arrow-left"></i><span class="sidebar-texto">Sair</span>
            </a>
        </aside>
        <div class="sidebar-fundo" id="sidebar-fundo"></div>
        <div class="flex-grow-1 bg-light conteudo">
            <header class="topbar d-flex justify-content-between align-items-center gap-3">
                <button type="button" class="btn btn-light border sidebar-toggle" id="sidebar-toggle" aria-label="Menu">
                    <i class="bi bi-list"></i>
                </button>
                <h5 class="m-0 me-auto">
                    <?= $titulo ?? 'SalAveiro' ?>
                    <?php if (!$podeGerir && !in_array($current, ['dashboard', 'externos'])): ?>
                        <span class="badge text-bg-light border fw-normal ms-2"><i class="bi bi-eye"></i> Só consulta</span>
                    <?php endif; ?>
                </h5>
                <div>
                    <i class="bi bi-person-circle"></i> <?= htmlspecialchars($_SESSION['username']) ?>
                    <span class="badge text-bg-<?= $corPerfil[$_SESSION['perfil']] ?? 'secondary' ?>"><?= $_SESSION['perfil'] ?></span>
                </div>
            </header>

            <main class="p-4">
                <?php if (isset($_SESSION['msg'])): ?>
                    <div class="alert alert-<?= $_SESSION['msg']['tipo'] ?> alert-dismissible fade show">
                        <?= $_SESSION['msg']['texto'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['msg']); ?>
                <?php endif; ?>