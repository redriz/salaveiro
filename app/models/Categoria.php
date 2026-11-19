<?php
require_once 'config/Database.php';

class Categoria
{
    private $conn;
    private $table = 'categorias_produto';

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function getAll($search)
    {
        $query = "SELECT c.*, COUNT(p.id_produto) AS total_produtos
                  FROM categorias_produto c
                  LEFT JOIN produtos p ON p.id_categoria = c.id_categoria
                  WHERE c.nome LIKE :search
                  GROUP BY c.id_categoria
                  ORDER BY c.nome";
        $stmt = $this->conn->prepare($query);
        $search = "%$search%";
        $stmt->bindParam(':search', $search);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $query = "SELECT c.*, (SELECT COUNT(*) FROM produtos p WHERE p.id_categoria = c.id_categoria) AS total_produtos
                  FROM categorias_produto c WHERE c.id_categoria = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function create($c)
    {
        $query = "INSERT INTO categorias_produto (nome, descricao) VALUES (:nome, :descricao)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':nome'      => $c['nome'],
            ':descricao' => $c['descricao'] ?: null
        ]);
    }

    public function update($id, $c)
    {
        $query = "UPDATE categorias_produto SET nome = :nome, descricao = :descricao WHERE id_categoria = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':nome'      => $c['nome'],
            ':descricao' => $c['descricao'] ?: null,
            ':id'        => $id
        ]);
    }

    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id_categoria = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
