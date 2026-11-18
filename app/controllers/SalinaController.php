<?php
require_once 'app/models/Salina.php';

class SalinaController
{
    private $salinaModel;

    public function __construct()
    {
        $this->salinaModel = new Salina();
    }

    public function index()
    {
        $search = $_GET['search'] ?? '';
        $estado = $_GET['estado'] ?? '';
        $erros  = [];

        $salina = [
            'id_salina' => '',
            'nome' => '',
            'localizacao' => '',
            'area_hectares' => '',
            'descricao' => '',
            'estado' => 'Ativa'
        ];

        if (!empty($_GET['id'])) {
            $salina = $this->salinaModel->getById($_GET['id']);
            if (!$salina) {
                $_SESSION['msg'] = ['tipo' => 'warning', 'texto' => 'Salina não encontrado.'];
                header('Location: index.php?page=salinas');
                exit;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $salina['nome'] = trim($_POST['nome'] ?? '');
            $salina['localizacao'] = trim($_POST['localizacao'] ?? '');
            $salina['area_hectares'] = ($_POST['area_hectares'] ?? '');
            $salina['descricao'] = trim($_POST['descricao'] ?? '');
            $salina['estado'] = $_POST['estado'] ?? 'Ativa';

            if (!is_numeric($salina['area_hectares']) || $salina['area_hectares'] <= 0) {
                $erros['area_hectares'] = 'A área deve ser superior a 0.';
            }
            if ($salina['nome'] === '') {
                $erros['nome'] = 'O nome é obrigatório.';
            }
            if ($salina['localizacao'] === '') {
                $erros['localizacao'] = 'A localização é obrigatória.';
            }

            if (empty($erros)) {
                try {
                    if ($salina['id_salina'] === '') {
                        $this->salinaModel->create($salina);
                    } else {
                        $this->salinaModel->update($salina['id_salina'], $salina);
                    }
                    $_SESSION['msg'] = ['tipo' => 'success', 'texto' => 'Salina guardado com sucesso.'];
                    header('Location: index.php?page=salinas');
                    exit;
                } catch (PDOException $e) {
                    $erros['nome'] = 'Já existe uma salina com esse nome.';
                }
            }
        }

        $salinas = $this->salinaModel->getAll($search, $estado);

        require 'app/views/salinas/index.php';
    }

    public function delete()
    {
        $id = $_POST['id'] ?? 0;

        try {
            $this->salinaModel->delete($_POST['id'] ?? 0);
            $_SESSION['msg'] = ['tipo' => 'success', 'texto' => 'Salina eliminada com sucesso.'];
        } catch (PDOException $e) {
            $_SESSION['msg'] = ['tipo' => 'danger', 'texto' => 'Não é possível eliminar: a salina tem tanques associados.'];
        }

        header('Location: index.php?page=salinas');
        exit;
    }
}
