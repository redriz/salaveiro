<?php
require_once 'app/models/Utilizador.php';

class UtilizadorController
{
    private $utilizadorModel;

    public function __construct()
    {
        $this->utilizadorModel = new Utilizador();
    }

    public function index()
    {
        $search = $_GET['search'] ?? '';
        $estado = $_GET['estado'] ?? '';
        $erros  = [];

        $utilizador = [
            'id_utilizador' => '',
            'nome' => '',
            'username' => '',
            'email' => '',
            'id_perfil' => '',
            'id_trabalhador' => '',
            'estado' => 'Ativo'
        ];

        if (!empty($_GET['id'])) {
            $utilizador = $this->utilizadorModel->getById($_GET['id']);
            if (!$utilizador) {
                $_SESSION['msg'] = ['tipo' => 'warning', 'texto' => 'Utilizador não encontrado.'];
                header('Location: index.php?page=utilizadores');
                exit;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $utilizador['nome'] = trim($_POST['nome'] ?? '');
            $utilizador['username'] = trim($_POST['username'] ?? '');
            $utilizador['email'] = trim($_POST['email'] ?? '');
            $utilizador['id_perfil'] = ($_POST['id_perfil'] ?? '');
            $utilizador['id_trabalhador'] = ($_POST['id_trabalhador'] ?? '');
            $utilizador['estado'] = isset($_POST['estado']) ? 'Ativo' : 'Inativo';
            $utilizador['palavra_passe'] = $_POST['palavra_passe'] ?? '';

            if ($utilizador['nome'] === '') {
                $erros['nome'] = 'O nome é obrigatório.';
            }
            if (!filter_var($utilizador['email'], FILTER_VALIDATE_EMAIL)) {
                $erros['email'] = 'Introduza um email válido.';
            }
            if ($utilizador['id_utilizador'] === '' && strlen($utilizador['palavra_passe']) < 8) {
                $erros['palavra_passe'] = 'A palavra-passe deve ter pelo menos 8 caracteres.';
            }
            if ($utilizador['username'] === '') {
                $erros['username'] = 'O username é obrigatório.';
            }
            if ($utilizador['id_perfil'] === '') {
                $erros['id_perfil'] = 'O perfil é obrigatório.';
            }

            $idAtual = $utilizador['id_utilizador'] ?: null;
            if (!isset($erros['username']) && $this->utilizadorModel->existeValor('username', $utilizador['username'], $idAtual)) {
                $erros['username'] = 'Este username já está a ser utilizado.';
            }
            if (!isset($erros['email']) && $this->utilizadorModel->existeValor('email', $utilizador['email'], $idAtual)) {
                $erros['email'] = 'Este email já está a ser utilizado.';
            }
            if ($utilizador['id_trabalhador'] !== '' && $this->utilizadorModel->existeValor('id_trabalhador', $utilizador['id_trabalhador'], $idAtual)) {
                $erros['id_trabalhador'] = 'Este trabalhador já está associado a outro utilizador.';
            }

            if (empty($erros)) {
                try {
                    if ($utilizador['id_utilizador'] === '') {
                        $this->utilizadorModel->create($utilizador);
                    } else {
                        $this->utilizadorModel->update($utilizador['id_utilizador'], $utilizador);
                    }
                    $_SESSION['msg'] = ['tipo' => 'success', 'texto' => 'Utilizador guardado com sucesso.'];
                    header('Location: index.php?page=utilizadores');
                    exit;
                } catch (PDOException $e) {
                    $_SESSION['msg'] = ['tipo' => 'danger', 'texto' => 'Não foi possível guardar o utilizador.'];
                }
            }
        }

        $utilizadores = $this->utilizadorModel->getAll($search, $estado);
        $perfis = $this->utilizadorModel->getPerfis();
        $trabalhadores = $this->utilizadorModel->getTrabalhadores();

        require 'app/views/utilizadores/index.php';
    }

    public function delete()
    {
        $id = $_POST['id'] ?? 0;

        if ($id == $_SESSION['user_id']) {
            $_SESSION['msg'] = ['tipo' => 'danger', 'texto' => 'Não pode eliminar o seu próprio utilizador.'];
        } else {
            try {
                $this->utilizadorModel->delete($_POST['id'] ?? 0);
                $_SESSION['msg'] = ['tipo' => 'success', 'texto' => 'Utilizador eliminado com sucesso.'];
            } catch (PDOException $e) {
                $_SESSION['msg'] = ['tipo' => 'danger', 'texto' => 'Não é possível eliminar: o utilizador tem colheitas ou encomendas registadas.'];
            }
        }
        header('Location: index.php?page=utilizadores');
        exit;
    }
}
