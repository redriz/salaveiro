<?php
require_once 'app/models/Categoria.php';

class CategoriaController
{
    private $categoriaModel;

    public function __construct()
    {
        $this->categoriaModel = new Categoria();
    }

    public function index()
    {
        $search = $_GET['search'] ?? '';
        $erros  = [];

        $categoria = [
            'id_categoria' => '',
            'nome' => '',
            'descricao' => ''
        ];

        if (!empty($_GET['id'])) {
            $categoria = $this->categoriaModel->getById($_GET['id']);
            if (!$categoria) {
                $_SESSION['msg'] = ['tipo' => 'warning', 'texto' => 'Categoria não encontrada.'];
                header('Location: index.php?page=categorias');
                exit;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $categoria['nome'] = trim($_POST['nome'] ?? '');
            $categoria['descricao'] = trim($_POST['descricao'] ?? '');

            if ($categoria['nome'] === '') {
                $erros['nome'] = 'O nome é obrigatório.';
            }

            if (empty($erros)) {
                try {
                    if ($categoria['id_categoria'] === '') {
                        $this->categoriaModel->create($categoria);
                    } else {
                        $this->categoriaModel->update($categoria['id_categoria'], $categoria);
                    }
                    $_SESSION['msg'] = ['tipo' => 'success', 'texto' => 'Categoria guardada com sucesso.'];
                    header('Location: index.php?page=categorias');
                    exit;
                } catch (PDOException $e) {
                    $erros['nome'] = 'Já existe uma categoria com esse nome.';
                }
            }
        }

        $categorias = $this->categoriaModel->getAll($search);

        require 'app/views/categorias/index.php';
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['id'])) {
            header('Location: index.php?page=categorias');
            exit;
        }

        try {
            $this->categoriaModel->delete($_POST['id']);
            $_SESSION['msg'] = ['tipo' => 'success', 'texto' => 'Categoria eliminada com sucesso.'];
        } catch (PDOException $e) {
            $_SESSION['msg'] = ['tipo' => 'danger', 'texto' => 'Não é possível eliminar: a categoria tem produtos associados.'];
        }

        header('Location: index.php?page=categorias');
        exit;
    }
}
