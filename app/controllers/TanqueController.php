<?php
require_once 'app/models/Tanque.php';

class TanqueController
{
    private $tanqueModel;

    public function __construct()
    {
        $this->tanqueModel = new Tanque();
    }

    public function index()
    {
        $search = $_GET['search'] ?? '';
        $estado = $_GET['estado'] ?? '';
        $erros  = [];

        $tanque = [
            'id_tanque' => '',
            'id_salina' => '',
            'codigo' => '',
            'area_m2' => '',
            'capacidade_maxima_kg' => '',
            'estado' => 'Ativo'
        ];


        if (!empty($_GET['id'])) {
            $tanque = $this->tanqueModel->getById($_GET['id']);
            if (!$tanque) {
                $_SESSION['msg'] = ['tipo' => 'warning', 'texto' => 'Tanque não encontrado.'];
                header('Location: index.php?page=tanques');
                exit;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tanque['id_salina'] = $_POST['id_salina'] ?? '';
            $tanque['codigo'] = trim($_POST['codigo'] ?? '');
            $tanque['area_m2'] = ($_POST['area_m2'] ?? '');
            $tanque['capacidade_maxima_kg'] = ($_POST['capacidade_maxima_kg'] ?? '');
            $tanque['estado'] = $_POST['estado'] ?? 'Ativo';

            if (!is_numeric($tanque['area_m2']) || $tanque['area_m2'] <= 0) {
                $erros['area_m2'] = 'A área deve ser superior a 0.';
            }
            if (!is_numeric($tanque['capacidade_maxima_kg']) || $tanque['capacidade_maxima_kg'] <= 0) {
                $erros['capacidade_maxima_kg'] = 'A capacidade deve ser superior a 0.';
            }
            if ($tanque['codigo'] === '') {
                $erros['codigo'] = 'O código é obrigatório.';
            }
            if ($tanque['id_salina'] === '') {
                $erros['id_salina'] = 'A salina é obrigatória.';
            }

            if (empty($erros)) {
                try {
                    if ($tanque['id_tanque'] === '') {
                        $this->tanqueModel->create($tanque);
                    } else {
                        $this->tanqueModel->update($tanque['id_tanque'], $tanque);
                    }
                    $_SESSION['msg'] = ['tipo' => 'success', 'texto' => 'Tanque guardado com sucesso.'];
                    header('Location: index.php?page=tanques');
                    exit;
                } catch (PDOException $e) {
                    $erros['codigo'] = 'Já existe um tanque com esse código para esta salina.';
                }
            }
        }

        $tanques = $this->tanqueModel->getAll($search, $estado);
        $salinas = $this->tanqueModel->getSalinas();

        require 'app/views/tanques/index.php';
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['id'])) {
            header('Location: index.php?page=tanques');
            exit;
        }

        try {
            $this->tanqueModel->delete($_POST['id']);
            $_SESSION['msg'] = ['tipo' => 'success', 'texto' => 'Tanque eliminado com sucesso.'];
        } catch (PDOException $e) {
            $_SESSION['msg'] = ['tipo' => 'danger', 'texto' => 'Não é possível eliminar: o tanque tem colheitas registadas.'];
        }

        header('Location: index.php?page=tanques');
        exit;
    }
}
