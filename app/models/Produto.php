<?php
require_once 'config/Database.php';

class Produto
{
    private $conn;
    private $table = 'produtos';

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function getAll($search, $estado)
    {
        $query = "SELECT p.*, c.nome AS categoria
                  FROM produtos p
                  INNER JOIN categorias_produto c ON c.id_categoria = p.id_categoria
                  WHERE (p.nome LIKE :s1 OR c.nome LIKE :s2)";

        if ($estado !== '') {
            $query .= " AND p.estado = :estado";
        }
        $query .= " ORDER BY c.nome, p.nome";

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
        $query = "SELECT * FROM " . $this->table . " WHERE id_produto = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function create($p)
    {
        $query = "INSERT INTO produtos (id_categoria, nome, peso_kg, preco_venda, stock_unidades, estado)
                  VALUES (:categoria, :nome, :peso, :preco, :stock, :estado)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':categoria' => $p['id_categoria'],
            ':nome'      => $p['nome'],
            ':peso'      => $p['peso_kg'],
            ':preco'     => $p['preco_venda'],
            ':stock'     => $p['stock_unidades'],
            ':estado'    => $p['estado']
        ]);
    }

    public function update($id, $p)
    {
        $query = "UPDATE produtos SET id_categoria = :categoria, nome = :nome, peso_kg = :peso,
                  preco_venda = :preco, stock_unidades = :stock, estado = :estado
                  WHERE id_produto = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':categoria' => $p['id_categoria'],
            ':nome'      => $p['nome'],
            ':peso'      => $p['peso_kg'],
            ':preco'     => $p['preco_venda'],
            ':stock'     => $p['stock_unidades'],
            ':estado'    => $p['estado'],
            ':id'        => $id
        ]);
    }

    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id_produto = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function getCategorias()
    {
        return $this->conn->query("SELECT id_categoria, nome FROM categorias_produto ORDER BY nome")->fetchAll();
    }

    public function adicionarStock($id, $quantidade)
    {
        $stmt = $this->conn->prepare("UPDATE produtos SET stock_unidades = stock_unidades + :q WHERE id_produto = :id");
        return $stmt->execute([':q' => $quantidade, ':id' => $id]);
    }
}
