<?php
require_once 'app/models/Colheita.php';

class ProducaoController
{
    private $colheitaModel;

    public function __construct()
    {
        $this->colheitaModel = new Colheita();
    }

    public function index()
    {
        $idSalina = $_GET['salina'] ?? '';
        $erros = [];

        $tanques       = $this->colheitaModel->getTanques();
        $salinas       = $this->colheitaModel->getSalinas();
        $trabalhadores = $this->colheitaModel->getTrabalhadores();

        $colheita = [
            'id_colheita' => '',
            'id_tanque' => '',
            'id_trabalhador' => '',
            'data_colheita' => date('Y-m-d'),
            'quantidade_kg' => '',
            'observacoes' => ''
        ];

        if (!empty($_GET['id'])) {
            $colheita = $this->colheitaModel->getById($_GET['id']);
            if (!$colheita) {
                $_SESSION['msg'] = ['tipo' => 'warning', 'texto' => 'Registo de produção não encontrado.'];
                header('Location: index.php?page=producao');
                exit;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $colheita['id_tanque'] = $_POST['id_tanque'] ?? '';
            $colheita['id_trabalhador'] = $_POST['id_trabalhador'] ?? '';
            $colheita['data_colheita'] = $_POST['data_colheita'] ?? '';
            $colheita['quantidade_kg'] = $_POST['quantidade_kg'] ?? '';
            $colheita['observacoes'] = trim($_POST['observacoes'] ?? '');

            // procurar o tanque escolhido na lista, para saber o estado e a capacidade
            $tanqueEscolhido = null;
            foreach ($tanques as $t) {
                if ($t['id_tanque'] == $colheita['id_tanque']) {
                    $tanqueEscolhido = $t;
                }
            }

            if (!$tanqueEscolhido) {
                $erros['id_tanque'] = 'Selecione o tanque.';
            } elseif ($tanqueEscolhido['estado'] !== 'Ativo') {
                $erros['id_tanque'] = 'Só é possível registar produção em tanques ativos.';
            }
            if ($colheita['id_trabalhador'] === '') {
                $erros['id_trabalhador'] = 'Selecione o trabalhador responsável.';
            }
            if ($colheita['data_colheita'] === '' || $colheita['data_colheita'] > date('Y-m-d')) {
                $erros['data_colheita'] = 'Indique uma data válida (não futura).';
            }
            if (!is_numeric($colheita['quantidade_kg']) || $colheita['quantidade_kg'] <= 0) {
                $erros['quantidade_kg'] = 'A quantidade deve ser superior a 0.';
            } elseif ($tanqueEscolhido && $colheita['quantidade_kg'] > $tanqueEscolhido['capacidade_maxima_kg']) {
                $erros['quantidade_kg'] = 'A quantidade excede a capacidade do tanque ('
                    . number_format($tanqueEscolhido['capacidade_maxima_kg'], 2, ',', ' ') . ' kg).';
            }

            if (empty($erros)) {
                try {
                    if ($colheita['id_colheita'] === '') {
                        $this->colheitaModel->create($colheita, $_SESSION['user_id']);
                    } else {
                        $this->colheitaModel->update($colheita['id_colheita'], $colheita);
                    }
                    $_SESSION['msg'] = ['tipo' => 'success', 'texto' => 'Produção registada com sucesso.'];
                    header('Location: index.php?page=producao');
                    exit;
                } catch (PDOException $e) {
                    $erros['geral'] = 'Não foi possível guardar o registo. Tente novamente.';
                }
            }
        }

        $colheitas = $this->colheitaModel->getAll($idSalina);

        require 'app/views/producao/index.php';
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['id'])) {
            header('Location: index.php?page=producao');
            exit;
        }

        try {
            $this->colheitaModel->delete($_POST['id']);
            $_SESSION['msg'] = ['tipo' => 'success', 'texto' => 'Registo de produção eliminado com sucesso.'];
        } catch (PDOException $e) {
            $_SESSION['msg'] = ['tipo' => 'danger', 'texto' => 'Não foi possível eliminar o registo.'];
        }

        header('Location: index.php?page=producao');
        exit;
    }
}
