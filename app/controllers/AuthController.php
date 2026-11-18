<?php
require_once 'app/models/Utilizador.php';

class AuthController
{
    private $utilizadorModel;

    public function __construct()
    {
        $this->utilizadorModel = new Utilizador();
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $erro = '';
        $username = '';

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $user = $this->utilizadorModel->getByUsername($username);

        if (!$user || !password_verify($password, $user['palavra_passe'])) {
            $erro = 'Credenciais inválidas. Tente novamente.';
        } elseif ($user['estado'] !== 'Ativo') {
            $erro = 'Utilizador inativo. Contacte o administrador.';
        } else {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id_utilizador'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['perfil'] = $user['perfil'];
            $this->utilizadorModel->updateUltimoAcesso($user['id_utilizador']);
            header('Location: index.php?page=dashboard&action=index');
            exit;
        }

        require 'app/views/auth/login.php';
    }
    }

    public function logout()
    {
        session_destroy();
        header('Location: index.php?page=auth&action=login');
        exit;
    }
}