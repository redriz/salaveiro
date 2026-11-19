<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SalAveiro - Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/tema.js"></script>
</head>

<body class="login-bg d-flex align-items-center justify-content-center min-vh-100">
    <div class="card shadow border-0 p-4" style="width: 100%; max-width: 420px;">
        <div class="text-center mb-4">
            <div class="login-icon mx-auto mb-2"><i class="bi bi-droplet-fill"></i></div>
            <h2 class="mb-0">SalAveiro</h2>
            <small class="text-muted">Gestão de salinas, produção e encomendas</small>
        </div>
        <form method="post">
            <?php if ($erro): ?>
                <div class="alert alert-danger py-2"><i class="bi bi-exclamation-triangle"></i> <?= $erro ?></div>
            <?php endif; ?>
            <label for="username" class="form-label fw-semibold">Utilizador</label>
            <div class="input-group mb-3">
                <span class="input-group-text"><i class="bi bi-person"></i></span>
                <input type="text" id="username" name="username" class="form-control" required
                    value="<?= htmlspecialchars($username) ?>">
            </div>
            <label for="password" class="form-label fw-semibold">Palavra-passe</label>
            <div class="input-group mb-3">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <button class="btn btn-primary w-100 btn-lg">Entrar</button>
            <p class="text-center text-muted small mt-3 mb-0">Acesso reservado a utilizadores registados</p>
        </form>
    </div>
</body>

</html>