<?php
require_once 'app/models/Produto.php';

class ProdutoController
{
    private $produtoModel;

    public function __construct()
    {
        $this->produtoModel = new Produto();
    }

    public function index()
    {
        $search = $_GET['search'] ?? '';
        $estado = $_GET['estado'] ?? '';
        $erros  = [];

        $produto = [
            'id_produto' => '',
            'id_categoria' => '',
            'nome' => '',
            'peso_kg' => '',
            'preco_venda' => '',
            'stock_unidades' => 0,
            'estado' => 'Ativo'
        ];

        if (!empty($_GET['id'])) {
            $produto = $this->produtoModel->getById($_GET['id']);
            if (!$produto) {
                $_SESSION['msg'] = ['tipo' => 'warning', 'texto' => 'Produto não encontrado.'];
                header('Location: index.php?page=produtos');
                exit;
            }
        }

        $detalhe = null;
        if (!empty($_GET['ver'])) {
            $detalhe = $this->produtoModel->getById($_GET['ver']);
            if (!$detalhe) {
                $_SESSION['msg'] = ['tipo' => 'warning', 'texto' => 'Produto não encontrado.'];
                header('Location: index.php?page=produtos');
                exit;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $produto['id_categoria'] = $_POST['id_categoria'] ?? '';
            $produto['nome'] = trim($_POST['nome'] ?? '');
            $produto['peso_kg'] = $_POST['peso_kg'] ?? '';
            $produto['preco_venda'] = $_POST['preco_venda'] ?? '';
            $produto['stock_unidades'] = $_POST['stock_unidades'] ?? '';
            $produto['estado'] = isset($_POST['estado']) ? 'Ativo' : 'Inativo';

            if ($produto['id_categoria'] === '') {
                $erros['id_categoria'] = 'A categoria é obrigatória.';
            }
            if ($produto['nome'] === '') {
                $erros['nome'] = 'O nome é obrigatório.';
            }
            if (!is_numeric($produto['peso_kg']) || $produto['peso_kg'] <= 0) {
                $erros['peso_kg'] = 'O peso deve ser superior a 0.';
            }
            if (!is_numeric($produto['preco_venda']) || $produto['preco_venda'] <= 0) {
                $erros['preco_venda'] = 'O preço deve ser superior a 0.';
            }
            if (!ctype_digit((string) $produto['stock_unidades'])) {
                $erros['stock_unidades'] = 'O stock deve ser um número inteiro igual ou superior a 0.';
            }

            if (empty($erros)) {
                try {
                    if ($produto['id_produto'] === '') {
                        $this->produtoModel->create($produto);
                    } else {
                        $this->produtoModel->update($produto['id_produto'], $produto);
                    }
                    $_SESSION['msg'] = ['tipo' => 'success', 'texto' => 'Produto guardado com sucesso.'];
                    header('Location: index.php?page=produtos');
                    exit;
                } catch (PDOException $e) {
                    $erros['nome'] = 'Já existe um produto com esse nome e peso.';
                }
            }
        }

        $produtos = $this->produtoModel->getAll($search, $estado);
        $categorias = $this->produtoModel->getCategorias();

        require 'app/views/produtos/index.php';
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['id'])) {
            header('Location: index.php?page=produtos');
            exit;
        }

        try {
            $this->produtoModel->delete($_POST['id']);
            $_SESSION['msg'] = ['tipo' => 'success', 'texto' => 'Produto eliminado com sucesso.'];
        } catch (PDOException $e) {
            $_SESSION['msg'] = ['tipo' => 'danger', 'texto' => 'Não é possível eliminar: o produto está em encomendas.'];
        }

        header('Location: index.php?page=produtos');
        exit;
    }
}
