<?php
require_once 'app/models/Cliente.php';

class ClienteController
{
    private $clienteModel;

    public function __construct()
    {
        $this->clienteModel = new Cliente();
    }

    public function index()
    {
        $search = $_GET['search'] ?? '';
        $estado = $_GET['estado'] ?? '';
        $erros  = [];

        $cliente = [
            'id_cliente' => '',
            'nome' => '',
            'nif' => '',
            'telefone' => '',
            'email' => '',
            'morada' => '',
            'estado' => 'Ativo'
        ];

        if (!empty($_GET['id'])) {
            $cliente = $this->clienteModel->getById($_GET['id']);
            if (!$cliente) {
                $_SESSION['msg'] = ['tipo' => 'warning', 'texto' => 'Cliente não encontrado.'];
                header('Location: index.php?page=clientes');
                exit;
            }
        }

        $detalhe = null;
        if (!empty($_GET['ver'])) {
            $detalhe = $this->clienteModel->getById($_GET['ver']);
            if (!$detalhe) {
                $_SESSION['msg'] = ['tipo' => 'warning', 'texto' => 'Cliente não encontrado.'];
                header('Location: index.php?page=clientes');
                exit;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cliente['nome'] = trim($_POST['nome'] ?? '');
            $cliente['nif'] = trim($_POST['nif'] ?? '');
            $cliente['telefone'] = trim($_POST['telefone'] ?? '');
            $cliente['email'] = trim($_POST['email'] ?? '');
            $cliente['morada'] = trim($_POST['morada'] ?? '');
            $cliente['estado'] = isset($_POST['estado']) ? 'Ativo' : 'Inativo';

            if ($cliente['nome'] === '') {
                $erros['nome'] = 'O nome é obrigatório.';
            }
            if (!preg_match('/^\d{9}$/', $cliente['nif'])) {
                $erros['nif'] = 'O NIF deve ter 9 dígitos.';
            }
            if (!preg_match('/^\d{9}$/', $cliente['telefone'])) {
                $erros['telefone'] = 'O telefone deve ter 9 dígitos.';
            }
            if (!filter_var($cliente['email'], FILTER_VALIDATE_EMAIL)) {
                $erros['email'] = 'Introduza um email válido.';
            }
            if ($cliente['morada'] === '') {
                $erros['morada'] = 'A morada é obrigatória.';
            }

            $idAtual = $cliente['id_cliente'] ?: null;
            if (!isset($erros['nif']) && $this->clienteModel->existeValor('nif', $cliente['nif'], $idAtual)) {
                $erros['nif'] = 'Já existe um cliente com esse NIF.';
            }
            if (!isset($erros['email']) && $this->clienteModel->existeValor('email', $cliente['email'], $idAtual)) {
                $erros['email'] = 'Já existe um cliente com esse email.';
            }

            if (empty($erros)) {
                try {
                    if ($cliente['id_cliente'] === '') {
                        $this->clienteModel->create($cliente);
                    } else {
                        $this->clienteModel->update($cliente['id_cliente'], $cliente);
                    }
                    $_SESSION['msg'] = ['tipo' => 'success', 'texto' => 'Cliente guardado com sucesso.'];
                    header('Location: index.php?page=clientes');
                    exit;
                } catch (PDOException $e) {
                    $_SESSION['msg'] = ['tipo' => 'danger', 'texto' => 'Não foi possível guardar o cliente.'];
                }
            }
        }

        $clientes = $this->clienteModel->getAll($search, $estado);

        require 'app/views/clientes/index.php';
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['id'])) {
            header('Location: index.php?page=clientes');
            exit;
        }

        try {
            $this->clienteModel->delete($_POST['id']);
            $_SESSION['msg'] = ['tipo' => 'success', 'texto' => 'Cliente eliminado com sucesso.'];
        } catch (PDOException $e) {
            $_SESSION['msg'] = ['tipo' => 'danger', 'texto' => 'Este cliente não pode ser eliminado porque tem encomendas registadas. Se já não for utilizado, edite-o e desmarque a opção "Ativo".'];
        }

        header('Location: index.php?page=clientes');
        exit;
    }
}
