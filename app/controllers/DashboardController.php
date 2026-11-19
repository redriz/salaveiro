<?php
require_once 'app/models/Dashboard.php';
require_once 'app/models/Api.php';

class DashboardController
{
    private $dashboardModel;

    public function __construct()
    {
        $this->dashboardModel = new Dashboard();
    }

    public function index()
    {
        $totais = $this->dashboardModel->totais();

        require 'app/views/dashboard/index.php';
    }

    public function api()
    {
        $api = new Api();
        $erroApi = '<div class="alert alert-danger mb-0"><i class="bi bi-exclamation-triangle"></i> <strong>Erro de comunicação.</strong> Não foi possível contactar a API ou a resposta é inválida.</div>';

        switch ($_GET['bloco'] ?? '') {
            case 'meteo':
                $meteo = $api->meteorologia();
                require 'app/views/dashboard/api_meteo.php';
                break;
            case 'precos':
                $precos = $api->precosReferencia();
                require 'app/views/dashboard/api_precos.php';
                break;
            case 'alertas':
                $corNivel = ['Baixo' => 'info', 'Médio' => 'warning', 'Elevado' => 'danger', 'Crítico' => 'dark'];
                $alertas = $api->alertasAtivos();
                require 'app/views/dashboard/api_alertas.php';
                break;
            default:
                http_response_code(404);
        }
    }
}
