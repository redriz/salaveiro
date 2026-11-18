<?php
require_once 'config/Database.php';

class Trabalhador
{
    private $conn;
    private $table = 'trabalhadores';

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function getAll($search, $estado)
    {
        $query = "SELECT t.*, f.nome AS funcao, s.nome AS salina
                  FROM trabalhadores t
                  INNER JOIN funcoes f ON f.id_funcao = t.id_funcao
                  LEFT JOIN salinas s ON s.id_salina = t.id_salina
                  WHERE (t.nome LIKE :s1 OR t.email LIKE :s2)";

        if ($estado !== '') {
            $query .= " AND t.estado = :estado";
        }
        $query .= " ORDER BY t.id_trabalhador";

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
        $query = "SELECT * FROM " . $this->table . " WHERE id_trabalhador = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function create($t)
    {
        $query = "INSERT INTO trabalhadores (id_funcao, id_salina, nome, email, telefone, data_admissao, estado)
                  VALUES (:funcao, :salina, :nome, :email, :telefone, :data, :estado)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':funcao' => $t['id_funcao'],
            ':salina' => $t['id_salina'] ?: null,
            ':nome' => $t['nome'],
            ':email' => $t['email'],
            ':telefone' => $t['telefone'],
            ':data' => $t['data_admissao'],
            ':estado' => $t['estado']
        ]);
    }

    public function update($id, $t)
    {
        $query = "UPDATE trabalhadores SET id_funcao = :funcao, id_salina = :salina,
          nome = :nome, email = :email, telefone = :telefone, data_admissao = :data, estado = :estado";
        $params = [
            ':funcao' => $t['id_funcao'],
            ':salina' => $t['id_salina'] ?: null,
            ':nome' => $t['nome'],
            ':email' => $t['email'],
            ':telefone' => $t['telefone'],
            ':data' => $t['data_admissao'],
            ':estado' => $t['estado']
        ];

        $query .= " WHERE id_trabalhador = :id";
        $params[':id'] = $id;
        $stmt = $this->conn->prepare($query);
        return $stmt->execute($params);
    }

    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id_trabalhador = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function getFuncoes()
    {
        return $this->conn->query("SELECT id_funcao, nome FROM funcoes ORDER BY nome")->fetchAll();
    }

    public function getSalinas()
    {
        return $this->conn->query("SELECT id_salina, nome FROM salinas ORDER BY nome")->fetchAll();
    }
}
