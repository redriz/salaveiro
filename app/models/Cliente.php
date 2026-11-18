<?php
require_once 'config/Database.php';

class Cliente
{
    private $conn;
    private $table = 'clientes';

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function getAll($search, $estado)
    {
        $query = "SELECT * FROM clientes WHERE (nome LIKE :s1 OR nif LIKE :s2)";

        if ($estado !== '') {
            $query .= " AND estado = :estado";
        }
        $query .= " ORDER BY nome";

        $stmt = $this->conn->prepare($query);
        $search = "%$search%";
        $stmt->bindParam(':s1', $search);
        $stmt->bindParam(':s2', $search);
        if ($estado !== '') {
            $stmt->bindParam(':estado', $estado);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id_cliente = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function create($c)
    {
        $query = "INSERT INTO clientes (nome, nif, telefone, email, morada, estado)
                  VALUES (:nome, :nif, :telefone, :email, :morada, :estado)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':nome' => $c['nome'],
            ':nif' => $c['nif'],
            ':telefone' => $c['telefone'],
            ':email' => $c['email'],
            ':morada' => $c['morada'],
            ':estado' => $c['estado']
        ]);
    }

    public function update($id, $c)
    {
        $query = "UPDATE clientes SET nome = :nome, nif = :nif, telefone = :telefone,
                  email = :email, morada = :morada, estado = :estado
                  WHERE id_cliente = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':nome' => $c['nome'],
            ':nif' => $c['nif'],
            ':telefone' => $c['telefone'],
            ':email' => $c['email'],
            ':morada' => $c['morada'],
            ':estado' => $c['estado'],
            ':id' => $id
        ]);
    }

    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id_cliente = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
