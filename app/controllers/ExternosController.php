<?php
require_once 'app/models/Api.php';

class ExternosController
{
    private $recursos = [
        'status' => 'Estado da API',
        'weather' => 'Meteorologia',
        'alerts' => 'Alertas',
        'prices' => 'Preços',
        'salt-types' => 'Tipos de sal',
        'statistics' => 'Estatísticas',
        'recommendations' => 'Recomendações',
        'news' => 'Notícias',
    ];

    private $comId = ['alerts', 'prices', 'salt-types', 'recommendations', 'news'];

    public function index()
    {
        $recursos = $this->recursos;
        $recurso  = $_GET['recurso'] ?? '';
        $pesquisa = trim($_GET['pesquisa'] ?? '');

        $resposta = null;
        $erro = '';
        $linhas = [];
        $colunas  = [];

        if ($recurso !== '') {
            if (!isset($this->recursos[$recurso])) {
                $erro = 'Recurso inválido.';
            } else {
                $endpoint = $recurso;
                if (ctype_digit($pesquisa) && in_array($recurso, $this->comId)) {
                    $endpoint .= '/' . $pesquisa;
                }

                $api = new Api();
                $resposta = $api->consultar($endpoint);

                if ($resposta === null || $resposta['dados'] === null) {
                    $erro = 'Não foi possível contactar a API ou a resposta é inválida. Tente novamente.';
                } elseif ($resposta['status'] !== 200) {
                    $detalhe = $resposta['dados']['detail'] ?? '';
                    $erro = is_string($detalhe) && $detalhe !== '' ? $detalhe : 'Pedido inválido.';
                } else {
                    $dados = $resposta['dados'];

                    if ($recurso === 'weather') {
                        $dados = $dados['daily'];
                    }

                    $linhas = array_is_list($dados) ? $dados : [$dados];

                    if ($pesquisa !== '' && !ctype_digit($pesquisa)) {
                        $linhas = array_values(array_filter($linhas, function ($l) use ($pesquisa) {
                            return stripos(json_encode($l, JSON_UNESCAPED_UNICODE), $pesquisa) !== false;
                        }));
                    }

                    $colunas = $linhas ? array_keys($linhas[0]) : [];
                }
            }
        }

        require 'app/views/externos/index.php';
    }
}
