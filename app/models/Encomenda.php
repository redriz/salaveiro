<?php
require_once 'config/Database.php';

class Encomenda
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function getAll($search, $estado)
    {
        $query = "SELECT v.*, COUNT(ei.id_produto) AS num_itens,
                         GROUP_CONCAT(p.nome ORDER BY p.nome SEPARATOR ', ') AS produtos
                  FROM vw_totais_encomenda v
                  LEFT JOIN encomenda_itens ei ON ei.id_encomenda = v.id_encomenda
                  LEFT JOIN produtos p ON p.id_produto = ei.id_produto
                  WHERE (v.cliente LIKE :s1 OR v.id_encomenda = :s2)";

        if ($estado !== '') {
            $query .= " AND v.estado = :estado";
        }
        $query .= " GROUP BY v.id_encomenda ORDER BY v.data_encomenda DESC";

        $stmt = $this->conn->prepare($query);
        $like = "%$search%";
        $stmt->bindParam(':s1', $like);
        $stmt->bindParam(':s2', $search);
        if ($estado !== '') {
            $stmt->bindParam(':estado', $estado);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function contarPorEstado()
    {
        return $this->conn->query("SELECT estado, COUNT(*) FROM encomendas GROUP BY estado")
            ->fetchAll(PDO::FETCH_KEY_PAIR);
    }

    public function getById($id)
    {
        $query = "SELECT e.*, c.nome AS cliente, c.nif, c.morada, c.email,
                         u.username AS registada_por, v.valor_total
                  FROM encomendas e
                  INNER JOIN clientes c ON c.id_cliente = e.id_cliente
                  INNER JOIN utilizadores u ON u.id_utilizador = e.id_utilizador_registo
                  INNER JOIN vw_totais_encomenda v ON v.id_encomenda = e.id_encomenda
                  WHERE e.id_encomenda = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getItens($id)
    {
        $query = "SELECT ei.*, p.nome, ei.quantidade * ei.preco_unitario AS subtotal
                  FROM encomenda_itens ei
                  INNER JOIN produtos p ON p.id_produto = ei.id_produto
                  WHERE ei.id_encomenda = :id
                  ORDER BY p.nome";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    private function inserirItem($idEncomenda, $idProduto, $quantidade)
    {
        $stmt = $this->conn->prepare("UPDATE produtos SET stock_unidades = stock_unidades - :q1
                                      WHERE id_produto = :id AND stock_unidades >= :q2");
        $stmt->execute([':q1' => $quantidade, ':q2' => $quantidade, ':id' => $idProduto]);

        if ($stmt->rowCount() === 0) {
            return false;
        }

        $stmt = $this->conn->prepare("INSERT INTO encomenda_itens (id_encomenda, id_produto, quantidade, preco_unitario)
                                      SELECT :enc, id_produto, :q, preco_venda FROM produtos WHERE id_produto = :id
                                      ON DUPLICATE KEY UPDATE quantidade = quantidade + VALUES(quantidade)");
        $stmt->execute([':enc' => $idEncomenda, ':q' => $quantidade, ':id' => $idProduto]);
        return true;
    }

    public function criar($e, $idUtilizador)
    {
        $this->conn->beginTransaction();

        $stmt = $this->conn->prepare("INSERT INTO encomendas (id_cliente, id_utilizador_registo, observacoes)
                                      VALUES (:cliente, :utilizador, :obs)");
        $stmt->execute([
            ':cliente' => $e['id_cliente'],
            ':utilizador' => $idUtilizador,
            ':obs' => $e['observacoes'] ?: null
        ]);
        $idEncomenda = $this->conn->lastInsertId();

        if (!$this->inserirItem($idEncomenda, $e['id_produto'], $e['quantidade'])) {
            $this->conn->rollBack();
            return false;
        }

        $this->conn->commit();
        return $idEncomenda;
    }

    public function adicionarItem($idEncomenda, $idProduto, $quantidade)
    {
        $this->conn->beginTransaction();

        if (!$this->inserirItem($idEncomenda, $idProduto, $quantidade)) {
            $this->conn->rollBack();
            return false;
        }

        $this->conn->commit();
        return true;
    }

    public function alterarEstado($id, $estado)
    {
        $stmt = $this->conn->prepare("UPDATE encomendas SET estado = :estado WHERE id_encomenda = :id");
        return $stmt->execute([':estado' => $estado, ':id' => $id]);
    }

    public function cancelar($id)
    {
        $this->conn->beginTransaction();

        $stmt = $this->conn->prepare("UPDATE produtos p
                                      INNER JOIN encomenda_itens ei ON ei.id_produto = p.id_produto
                                      SET p.stock_unidades = p.stock_unidades + ei.quantidade
                                      WHERE ei.id_encomenda = :id");
        $stmt->execute([':id' => $id]);

        $this->alterarEstado($id, 'Cancelada');

        $this->conn->commit();
        return true;
    }

    public function getClientes()
    {
        return $this->conn->query("SELECT id_cliente, nome FROM clientes WHERE estado = 'Ativo' ORDER BY nome")->fetchAll();
    }

    public function getProdutos()
    {
        return $this->conn->query("SELECT id_produto, nome, preco_venda, stock_unidades FROM produtos
                                   WHERE estado = 'Ativo' AND stock_unidades > 0 ORDER BY nome")->fetchAll();
    }
}
