<?php
$current = $_GET['page'] ?? 'dashboard';
if (in_array($current, ['utilizadores', 'trabalhadores', 'salinas', 'tanques', 'categorias', 'produtos'])) {
    $current = 'dados';
}

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
];

?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SalAveiro - Gestão</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div class="d-flex min-vh-100">
        <aside class="sidebar d-flex flex-column">
            <div class="sidebar-logo"><i class="bi bi-droplet-fill"></i> SalAveiro</div>

            <nav class="nav flex-column">
                <?php foreach ($menu as $key => $item): ?>
                    <a class="nav-link <?= $current === $key ? 'active' : '' ?>" href="index.php?page=<?= $key ?>">
                        <i class="bi <?= $item[1] ?>"></i> <?= $item[0] ?>
                    </a>
                <?php endforeach; ?>
            </nav>

            <a class="nav-link mt-auto sidebar-sair" href="index.php?page=auth&action=logout">
                <i class="bi bi-box-arrow-left"></i> Sair
            </a>
        </aside>
        <div class="flex-grow-1 bg-light">
            <header class="topbar d-flex justify-content-between align-items-center">
                <h5 class="m-0"><?= $titulo ?? 'SalAveiro' ?></h5>
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