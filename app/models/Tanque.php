<?php
require_once 'config/Database.php';

class Tanque
{
    private $conn;
    private $table = 'tanques';

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function getAll($search, $estado)
    {
        $query = "SELECT t.*, s.nome AS salina
                  FROM " . $this->table . " t
                  INNER JOIN salinas s ON s.id_salina = t.id_salina
                  WHERE (t.codigo LIKE :s1 OR s.nome LIKE :s2)";


        if ($estado !== '') {
            $query .= " AND t.estado = :estado";
        }
        $query .= " ORDER BY s.nome, t.codigo";

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
        $query = "SELECT t.*, s.nome AS salina
                  FROM " . $this->table . " t
                  INNER JOIN salinas s ON s.id_salina = t.id_salina
                  WHERE t.id_tanque = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function create($s)
    {
        $query = "INSERT INTO " . $this->table . " (id_salina, codigo, area_m2, capacidade_maxima_kg, estado)
                  VALUES (:id_salina, :codigo, :area_m2, :capacidade_maxima_kg, :estado)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':id_salina' => $s['id_salina'],
            ':codigo' => $s['codigo'],
            ':area_m2' => $s['area_m2'],
            ':capacidade_maxima_kg' => $s['capacidade_maxima_kg'],
            ':estado' => $s['estado']
        ]);
    }

    public function update($id, $s)
    {
        $query = "UPDATE " . $this->table . " SET id_salina = :id_salina, codigo = :codigo, area_m2 = :area_m2, capacidade_maxima_kg = :capacidade_maxima_kg, estado = :estado
                  WHERE id_tanque = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':id_salina' => $s['id_salina'],
            ':codigo' => $s['codigo'],
            ':area_m2' => $s['area_m2'],
            ':capacidade_maxima_kg' => $s['capacidade_maxima_kg'],
            ':estado' => $s['estado'],
            ':id' => $id
        ]);
    }

    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id_tanque = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function getSalinas()
    {
        return $this->conn->query("SELECT id_salina, nome FROM salinas ORDER BY nome")->fetchAll();
    }
}
