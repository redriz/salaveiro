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
        $api       = new Api();
        $meteo     = $api->meteorologia();
        $precos    = $api->precosReferencia();
        $alertas   = $api->alertasAtivos();

        require 'app/views/dashboard/index.php';
    }
}
