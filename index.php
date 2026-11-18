<?php
session_start();

$page = $_GET['page'] ?? 'dashboard';
$action = $_GET['action'] ?? 'index';

if (!isset($_SESSION['user_id']) && $page !== 'login') {
    header('Location: index.php?page=auth&action=login');
    exit;
}

switch ($page) {
    case 'auth':
        require_once 'app/controllers/AuthController.php';
        $controller = new AuthController();
        break;
        // dados, producao, comercial externos
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
