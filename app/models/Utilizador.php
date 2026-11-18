<?php

class Utilizador
{
    private $conn;
    private $table = 'utilizadores';

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function getByUsername($username)
    {
        $query = "SELECT u.*, p.nome AS perfil
                  FROM utilizadores u
                  INNER JOIN perfis p ON p.id_perfil = u.id_perfil
                  WHERE u.username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function updateUltimoAcesso($id)
    {
        $query = "UPDATE {$this->table} SET ultimo_acesso = NOW() WHERE id_utilizador = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}