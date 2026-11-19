<?php
require_once 'app/models/Produto.php';

class StockController
{
    private $produtoModel;
    private $stockMinimo = 100;

    public function __construct()
    {
        $this->produtoModel = new Produto();
    }

    public function index()
    {
        $search = $_GET['search'] ?? '';
        $stockMinimo = $this->stockMinimo;

        $produtos = $this->produtoModel->getAll($search, '');

        $totalUnidades = 0;
        $valorStock = 0;
        $stockBaixo = 0;
        foreach ($produtos as $p) {
            $totalUnidades += $p['stock_unidades'];
            $valorStock += $p['stock_unidades'] * $p['preco_venda'];
            if ($p['stock_unidades'] < $stockMinimo) {
                $stockBaixo++;
            }
        }

        $detalhe = null;
        if (!empty($_GET['ver'])) {
            $detalhe = $this->produtoModel->getById($_GET['ver']);
            if (!$detalhe) {
                $_SESSION['msg'] = ['tipo' => 'warning', 'texto' => 'Produto não encontrado.'];
                header('Location: index.php?page=stock');
                exit;
            }
        }

        require 'app/views/stock/index.php';
    }

    public function entrada()
    {
        $id = $_POST['id_produto'] ?? '';
        $quantidade = $_POST['quantidade'] ?? '';

        if ($id === '' || !ctype_digit((string) $quantidade) || $quantidade < 1) {
            $_SESSION['msg'] = ['tipo' => 'danger', 'texto' => 'Selecione o produto e indique uma quantidade inteira superior a 0.'];
        } else {
            $this->produtoModel->adicionarStock($id, $quantidade);
            $_SESSION['msg'] = ['tipo' => 'success', 'texto' => "Entrada de $quantidade unidades registada com sucesso."];
        }

        header('Location: index.php?page=stock');
        exit;
    }
}
