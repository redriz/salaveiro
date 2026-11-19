<?php
require_once 'app/models/Trabalhador.php';

class TrabalhadorController
{
    private $trabalhadorModel;

    public function __construct()
    {
        $this->trabalhadorModel = new Trabalhador();
    }

    public function index()
    {
        $search = $_GET['search'] ?? '';
        $estado = $_GET['estado'] ?? '';
        $erros  = [];

        $trabalhador = [
            'id_trabalhador' => '',
            'id_funcao' => '',
            'id_salina' => '',
            'nome' => '',
            'email' => '',
            'telefone' => '',
            'data_admissao' => '',
            'estado' => 'Ativo'
        ];

        if (!empty($_GET['id'])) {
            $trabalhador = $this->trabalhadorModel->getById($_GET['id']);
            if (!$trabalhador) {
                $_SESSION['msg'] = ['tipo' => 'warning', 'texto' => 'Trabalhador não encontrado.'];
                header('Location: index.php?page=trabalhadores');
                exit;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $trabalhador['id_funcao'] = ($_POST['id_funcao'] ?? '');
            $trabalhador['id_salina'] = ($_POST['id_salina'] ?? '');
            $trabalhador['nome'] = trim($_POST['nome'] ?? '');
            $trabalhador['email'] = trim($_POST['email'] ?? '');
            $trabalhador['telefone'] = ($_POST['telefone'] ?? '');
            $trabalhador['data_admissao'] = ($_POST['data_admissao'] ?? '');
            $trabalhador['estado'] = isset($_POST['estado']) ? 'Ativo' : 'Inativo';

            if ($trabalhador['id_funcao'] === '') {
                $erros['id_funcao'] = 'A função é obrigatória.';
            }
            if ($trabalhador['nome'] === '') {
                $erros['nome'] = 'O nome é obrigatório.';
            }
            if (!filter_var($trabalhador['email'], FILTER_VALIDATE_EMAIL)) {
                $erros['email'] = 'Introduza um email válido.';
            }
            if (!preg_match('/^\d{9}$/', $trabalhador['telefone'])) {
                $erros['telefone'] = 'O telefone deve ter 9 dígitos.';
            }
            if ($trabalhador['data_admissao'] === '' || $trabalhador['data_admissao'] > date('Y-m-d')) {
                $erros['data_admissao'] = 'Indique uma data de admissão válida (não futura).';
            }
            if (!isset($erros['email']) && $this->trabalhadorModel->existeEmail($trabalhador['email'], $trabalhador['id_trabalhador'] ?: null)) {
                $erros['email'] = 'Já existe um trabalhador com esse email.';
            }

            if (empty($erros)) {
                try {
                    if ($trabalhador['id_trabalhador'] === '') {
                        $this->trabalhadorModel->create($trabalhador);
                    } else {
                        $this->trabalhadorModel->update($trabalhador['id_trabalhador'], $trabalhador);
                    }
                    $_SESSION['msg'] = ['tipo' => 'success', 'texto' => 'Trabalhador guardado com sucesso.'];
                    header('Location: index.php?page=trabalhadores');
                    exit;
                } catch (PDOException $e) {
                    $_SESSION['msg'] = ['tipo' => 'danger', 'texto' => 'Não foi possível guardar o trabalhador.'];
                }
            }
        }

        $trabalhadores = $this->trabalhadorModel->getAll($search, $estado);
        $funcoes = $this->trabalhadorModel->getFuncoes();
        $salinas = $this->trabalhadorModel->getSalinas();

        require 'app/views/trabalhadores/index.php';
    }

    public function delete()
    {
        $id = $_POST['id'] ?? 0;

        if ($id == $_SESSION['user_id']) {
            $_SESSION['msg'] = ['tipo' => 'danger', 'texto' => 'Não pode eliminar o seu próprio utilizador.'];
        } else {
            try {
                $this->trabalhadorModel->delete($_POST['id'] ?? 0);
                $_SESSION['msg'] = ['tipo' => 'success', 'texto' => 'Trabalhador eliminado com sucesso.'];
            } catch (PDOException $e) {
                $_SESSION['msg'] = ['tipo' => 'danger', 'texto' => 'Não é possível eliminar: o trabalhador tem colheitas registadas.'];
            }
        }
        header('Location: index.php?page=trabalhadores');
        exit;
    }
}
