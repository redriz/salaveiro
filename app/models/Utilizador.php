<?php
require_once 'config/Database.php';

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

    public function getAll($search, $estado)
    {
        $query = "SELECT u.*, p.nome AS perfil, t.nome AS trabalhador
                  FROM utilizadores u
                  INNER JOIN perfis p ON p.id_perfil = u.id_perfil
                  LEFT JOIN trabalhadores t ON t.id_trabalhador = u.id_trabalhador
                  WHERE (u.username LIKE :s1 OR u.nome LIKE :s2)";
        if ($estado !== '') {
            $query .= " AND u.estado = :estado";
        }
        $query .= " ORDER BY u.id_utilizador";

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
        $query = "SELECT * FROM " . $this->table . " WHERE id_utilizador = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function existeValor($campo, $valor, $ignorarId = null)
    {
        $permitidos = ['username', 'email', 'id_trabalhador'];
        if (!in_array($campo, $permitidos, true)) {
            return false;
        }
        $query = "SELECT COUNT(*) FROM {$this->table} WHERE {$campo} = :valor";
        $params = [':valor' => $valor];
        if ($ignorarId) {
            $query .= " AND id_utilizador <> :id";
            $params[':id'] = $ignorarId;
        }
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }

    public function create($u)
    {
        $query = "INSERT INTO utilizadores (id_perfil, id_trabalhador, nome, username, email, palavra_passe, estado)
              VALUES (:perfil, :trabalhador, :nome, :username, :email, :pass, :estado)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':perfil' => $u['id_perfil'],
            ':trabalhador' => $u['id_trabalhador'] ?: null,
            ':nome' => $u['nome'],
            ':username' => $u['username'],
            ':email' => $u['email'],
            ':estado' => $u['estado'],
            ':pass' => password_hash($u['palavra_passe'], PASSWORD_DEFAULT)
        ]);
    }

    public function update($id, $u)
    {
        $query = "UPDATE utilizadores SET id_perfil = :perfil, id_trabalhador = :trabalhador,
          nome = :nome, username = :username, email = :email, estado = :estado";
        $params = [
                ':perfil' => $u['id_perfil'],
                ':trabalhador' => $u['id_trabalhador'] ?: null,
                ':nome' => $u['nome'],
                ':username' => $u['username'],
                ':email' => $u['email'],
                ':estado' => $u['estado']
            ];

        if ($u['palavra_passe'] !== '') {
            $query .= ", palavra_passe = :pass";
            $params[':pass'] = password_hash($u['palavra_passe'], PASSWORD_DEFAULT);
        }

        $query .= " WHERE id_utilizador = :id";
        $params[':id'] = $id;
        $stmt = $this->conn->prepare($query);
        return $stmt->execute($params);
    }

    public function delete($id) 
    {
        $query = "DELETE FROM " . $this->table . " WHERE id_utilizador = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function getPerfis() 
    {
        return $this->conn->query("SELECT * FROM perfis ORDER BY id_perfil")->fetchAll();
    }

    public function getTrabalhadores() 
    {
        return $this->conn->query("SELECT id_trabalhador, nome FROM trabalhadores ORDER BY nome")->fetchAll();
    }
}
