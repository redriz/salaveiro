<?php
session_start();

$page = $_GET['page'] ?? 'dashboard';
$action = $_GET['action'] ?? 'index';

if (!isset($_SESSION['user_id']) && $page !== 'auth') {
    header('Location: index.php?page=auth&action=login');
    exit;
}

switch ($page) {
    case 'auth':
        require_once 'app/controllers/AuthController.php';
        $controller = new AuthController();
        break;
    case 'dados':
    case 'utilizadores':
        require_once 'app/controllers/UtilizadorController.php';
        $controller = new UtilizadorController();
        break;
    case 'trabalhadores':
        require_once 'app/controllers/TrabalhadorController.php';
        $controller = new TrabalhadorController();
        break;
    case 'salinas':
        require_once 'app/controllers/SalinaController.php';
        $controller = new SalinaController();
        break;
    case 'tanques':
        require_once 'app/controllers/TanqueController.php';
        $controller = new TanqueController();
        break;
    case 'categorias':
        require_once 'app/controllers/CategoriaController.php';
        $controller = new CategoriaController();
        break;
    case 'produtos':
        require_once 'app/controllers/ProdutoController.php';
        $controller = new ProdutoController();
        break;
    case 'producao':
        require_once 'app/controllers/ProducaoController.php';
        $controller = new ProducaoController();
        break;
    // comercial externos
    case 'dashboard':
    default:
        require_once 'app/controllers/DashboardController.php';
        $controller = new DashboardController();
        break;
}

if (method_exists($controller, $action)) {
    $controller->$action();
} else {
    echo 'Ação não encontrada!';
}
