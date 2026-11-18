<?php
require_once 'app/models/Encomenda.php';

class EncomendaController
{
    private $encomendaModel;
    private $estados = ['Pendente', 'Confirmada', 'Em preparação', 'Expedida', 'Concluída', 'Cancelada'];

    public function __construct()
    {
        $this->encomendaModel = new Encomenda();
    }

    public function index()
    {
        $search = trim($_GET['search'] ?? '');
        $estado = $_GET['estado'] ?? '';
        $estados = $this->estados;
        $erros = [];

        // encomenda selecionada (painel de detalhe)
        $encomenda = null;
        $itens = [];
        if (!empty($_GET['id'])) {
            $encomenda = $this->encomendaModel->getById($_GET['id']);
            if (!$encomenda) {
                $_SESSION['msg'] = ['tipo' => 'warning', 'texto' => 'Encomenda não encontrada.'];
                header('Location: index.php?page=encomendas');
                exit;
            }
            $itens = $this->encomendaModel->getItens($encomenda['id_encomenda']);
        }

        // formulário "Nova encomenda"
        $nova = ['id_cliente' => '', 'id_produto' => '', 'quantidade' => 1, 'observacoes' => ''];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$encomenda) {
            $nova['id_cliente'] = $_POST['id_cliente'] ?? '';
            $nova['id_produto'] = $_POST['id_produto'] ?? '';
            $nova['quantidade'] = $_POST['quantidade'] ?? '';
            $nova['observacoes'] = trim($_POST['observacoes'] ?? '');

            if ($nova['id_cliente'] === '') {
                $erros['id_cliente'] = 'Selecione o cliente.';
            }
            if ($nova['id_produto'] === '') {
                $erros['id_produto'] = 'Selecione o produto.';
            }
            if (!ctype_digit((string) $nova['quantidade']) || $nova['quantidade'] < 1) {
                $erros['quantidade'] = 'A quantidade deve ser um número inteiro superior a 0.';
            }

            if (empty($erros)) {
                $id = $this->encomendaModel->criar($nova, $_SESSION['user_id']);
                if ($id) {
                    $_SESSION['msg'] = ['tipo' => 'success', 'texto' => "Encomenda #$id criada com sucesso."];
                    header("Location: index.php?page=encomendas&id=$id");
                    exit;
                }
                $erros['quantidade'] = 'Stock insuficiente para a quantidade pedida.';
            }
        }

        $contagem   = $this->encomendaModel->contarPorEstado();
        $encomendas = $this->encomendaModel->getAll($search, $estado);
        $clientes   = $this->encomendaModel->getClientes();
        $produtos   = $this->encomendaModel->getProdutos();

        require 'app/views/encomendas/index.php';
    }

    public function adicionarItem()
    {
        $id = $_POST['id'] ?? 0;
        $idProduto = $_POST['id_produto'] ?? '';
        $quantidade = $_POST['quantidade'] ?? '';
        $encomenda = $this->encomendaModel->getById($id);

        if (!$encomenda || $encomenda['estado'] !== 'Pendente') {
            $_SESSION['msg'] = ['tipo' => 'danger', 'texto' => 'Só é possível adicionar produtos a encomendas pendentes.'];
        } elseif ($idProduto === '' || !ctype_digit((string) $quantidade) || $quantidade < 1) {
            $_SESSION['msg'] = ['tipo' => 'danger', 'texto' => 'Selecione o produto e uma quantidade válida.'];
        } elseif (!$this->encomendaModel->adicionarItem($id, $idProduto, $quantidade)) {
            $_SESSION['msg'] = ['tipo' => 'danger', 'texto' => 'Stock insuficiente para a quantidade pedida.'];
        } else {
            $_SESSION['msg'] = ['tipo' => 'success', 'texto' => 'Produto adicionado à encomenda.'];
        }

        header("Location: index.php?page=encomendas&id=$id");
        exit;
    }

    public function estado()
    {
        $id = $_POST['id'] ?? 0;
        $novoEstado = $_POST['estado'] ?? '';
        $encomenda = $this->encomendaModel->getById($id);

        if (!$encomenda || in_array($encomenda['estado'], ['Concluída', 'Cancelada'])) {
            $_SESSION['msg'] = ['tipo' => 'danger', 'texto' => 'Esta encomenda já não pode ser alterada.'];
        } elseif (!in_array($novoEstado, $this->estados) || $novoEstado === 'Cancelada') {
            $_SESSION['msg'] = ['tipo' => 'danger', 'texto' => 'Estado inválido.'];
        } else {
            $this->encomendaModel->alterarEstado($id, $novoEstado);
            $_SESSION['msg'] = ['tipo' => 'success', 'texto' => "Encomenda #$id atualizada para \"$novoEstado\"."];
        }

        header("Location: index.php?page=encomendas&id=$id");
        exit;
    }

    public function cancelar()
    {
        $id = $_POST['id'] ?? 0;
        $encomenda = $this->encomendaModel->getById($id);

        if (!$encomenda || in_array($encomenda['estado'], ['Concluída', 'Cancelada'])) {
            $_SESSION['msg'] = ['tipo' => 'danger', 'texto' => 'Esta encomenda já não pode ser cancelada.'];
        } else {
            $this->encomendaModel->cancelar($id);
            $_SESSION['msg'] = ['tipo' => 'success', 'texto' => "Encomenda #$id cancelada. O stock foi reposto."];
        }

        header("Location: index.php?page=encomendas&id=$id");
        exit;
    }
}
