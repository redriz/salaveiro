<?php
require_once 'config/Database.php';

class Salina
{
    private $conn;
    private $table = 'salinas';

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function getAll($search, $estado)
    {
        $query = "SELECT * FROM salinas
                  WHERE (nome LIKE :s1 OR localizacao LIKE :s2)";

        if ($estado !== '') {
            $query .= " AND estado = :estado";
        }
        $query .= " ORDER BY id_salina";

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
        $query = "SELECT * FROM " . $this->table . " WHERE id_salina = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function create($s)
    {
        $query = "INSERT INTO salinas (nome, localizacao, area_hectares, descricao, estado)
                  VALUES (:nome, :localizacao, :area, :descricao, :estado)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':nome' => $s['nome'],
            ':localizacao' => $s['localizacao'],
            ':area' => $s['area_hectares'],
            ':descricao' => $s['descricao'] ?: null,
            ':estado' => $s['estado']
        ]);
    }

    public function update($id, $s)
    {
        $query = "UPDATE salinas SET nome = :nome, localizacao = :localizacao, area_hectares = :area,
                  descricao = :descricao, estado = :estado
                  WHERE id_salina = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':nome' => $s['nome'],
            ':localizacao' => $s['localizacao'],
            ':area' => $s['area_hectares'],
            ':descricao' => $s['descricao'] ?: null,
            ':estado' => $s['estado'],
            ':id' => $id
        ]);
    }

    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id_salina = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
