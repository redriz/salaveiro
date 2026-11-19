<?php
session_start();

$page = $_GET['page'] ?? 'dashboard';
$action = $_GET['action'] ?? 'index';

if (!isset($_SESSION['user_id']) && $page !== 'auth') {
    header('Location: index.php?page=auth&action=login');
    exit;
}

require_once 'app/helpers/permissoes.php';

// qualquer escrita (POST ou ação que não seja "index" ou "api") exige permissão
$escrita = !in_array($action, ['index', 'api']) || $_SERVER['REQUEST_METHOD'] === 'POST';

if ($action === 'api') {
    session_write_close();
}

if ($page !== 'auth' && $escrita && !podeGerir($page)) {
    $_SESSION['msg'] = ['tipo' => 'danger', 'texto' => 'Não tem permissão para realizar esta ação.'];
    header('Location: index.php?page=' . urlencode($page));
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
    case 'clientes':
        require_once 'app/controllers/ClienteController.php';
        $controller = new ClienteController();
        break;
    case 'comercial':
    case 'encomendas':
        require_once 'app/controllers/EncomendaController.php';
        $controller = new EncomendaController();
        break;
    case 'stock':
        require_once 'app/controllers/StockController.php';
        $controller = new StockController();
        break;
    case 'externos':
        require_once 'app/controllers/ExternosController.php';
        $controller = new ExternosController();
        break;
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
