<?php
require_once 'config/Database.php';

class Dashboard
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function totais() 
    {
        $query = "SELECT
        (SELECT COUNT(*) FROM salinas) AS salinas,
        (SELECT COUNT(*) FROM trabalhadores) AS trabalhadores,
        (SELECT COUNT(*) FROM colheitas) AS colheitas,
        (SELECT COALESCE(SUM(stock_unidades), 0) FROM produtos) AS qtd_produtos,
        (SELECT COUNT(*) FROM produtos) AS produtos,
        (SELECT COUNT(*) FROM clientes) AS clientes,
        (SELECT COUNT(*) FROM encomendas) AS encomendas";

    return $this->conn->query($query)->fetch();
    }
}