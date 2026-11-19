<?php
require_once 'config/Database.php';

class Colheita
{
    private $conn;
    private $table = 'colheitas';

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function getAll($idSalina)
    {
        $query = "SELECT c.*, t.codigo AS tanque, s.nome AS salina,
                  tr.nome AS trabalhador, u.username AS registado_por
                  FROM colheitas c
                  INNER JOIN tanques t ON t.id_tanque = c.id_tanque
                  INNER JOIN salinas s ON s.id_salina = t.id_salina
                  INNER JOIN trabalhadores tr ON tr.id_trabalhador = c.id_trabalhador
                  INNER JOIN utilizadores u ON u.id_utilizador = c.id_utilizador_registo";

        if ($idSalina !== '') {
            $query .= " WHERE s.id_salina = :salina";
        }
        $query .= " ORDER BY c.data_colheita DESC, c.id_colheita DESC";

        $stmt = $this->conn->prepare($query);
        if ($idSalina !== '') {
            $stmt->bindParam(':salina', $idSalina);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $query = "SELECT c.*, t.codigo AS tanque, s.nome AS salina,
                  tr.nome AS trabalhador, u.username AS registado_por
                  FROM colheitas c
                  INNER JOIN tanques t ON t.id_tanque = c.id_tanque
                  INNER JOIN salinas s ON s.id_salina = t.id_salina
                  INNER JOIN trabalhadores tr ON tr.id_trabalhador = c.id_trabalhador
                  INNER JOIN utilizadores u ON u.id_utilizador = c.id_utilizador_registo
                  WHERE c.id_colheita = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function create($c, $idUtilizador)
    {
        $query = "INSERT INTO colheitas (id_tanque, id_trabalhador, id_utilizador_registo, data_colheita, quantidade_kg, observacoes)
                  VALUES (:tanque, :trabalhador, :utilizador, :data, :quantidade, :obs)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':tanque' => $c['id_tanque'],
            ':trabalhador' => $c['id_trabalhador'],
            ':utilizador' => $idUtilizador,
            ':data' => $c['data_colheita'],
            ':quantidade' => $c['quantidade_kg'],
            ':obs' => $c['observacoes'] ?: null
        ]);
    }

    public function update($id, $c)
    {
        $query = "UPDATE colheitas SET id_tanque = :tanque, id_trabalhador = :trabalhador,
                  data_colheita = :data, quantidade_kg = :quantidade, observacoes = :obs
                  WHERE id_colheita = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':tanque' => $c['id_tanque'],
            ':trabalhador' => $c['id_trabalhador'],
            ':data' => $c['data_colheita'],
            ':quantidade' => $c['quantidade_kg'],
            ':obs' => $c['observacoes'] ?: null,
            ':id' => $id
        ]);
    }

    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id_colheita = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function getTanques()
    {
        return $this->conn->query("SELECT t.id_tanque, t.codigo, t.estado, t.capacidade_maxima_kg, s.nome AS salina
                                   FROM tanques t
                                   INNER JOIN salinas s ON s.id_salina = t.id_salina
                                   ORDER BY s.nome, t.codigo")->fetchAll();
    }

    public function getSalinas()
    {
        return $this->conn->query("SELECT id_salina, nome FROM salinas ORDER BY nome")->fetchAll();
    }

    public function getTrabalhadores()
    {
        return $this->conn->query("SELECT id_trabalhador, nome FROM trabalhadores WHERE estado = 'Ativo' ORDER BY nome")->fetchAll();
    }
}
