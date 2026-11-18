<?php
class Api
{
    private $baseUrl = 'http://192.168.1.91:5000/api/';

    private function get($endpoint)
    {
        $context = stream_context_create(['http' => ['timeout' => 3]]);
        $json = @file_get_contents($this->baseUrl . $endpoint, false, $context);

        if ($json === false) {
            return null;
        }
        return json_decode($json, true);
    }

    public function meteorologia()
    {
        $dados = $this->get('weather');
        return $dados['current'] ?? null;
    }

    public function precosReferencia()
    {
        $dados = $this->get('prices');
        if ($dados === null) {
            return null;
        }

        $precos = [];
        foreach ($dados as $p) {
            $precos[$p['saltTypeId']] = $p;
        }
        return $precos;
    }

    public function alertasAtivos()
    {
        $dados = $this->get('alerts');
        if ($dados === null) {
            return null;
        }

        $ativos = [];
        foreach ($dados as $a) {
            if ($a['active']) {
                $ativos[] = $a;
            }
        }
        return $ativos;
    }

    public function consultar($endpoint)
    {
        $ch = curl_init($this->baseUrl . $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);

        $json = curl_exec($ch);

        if ($json === false) {
            return null;
        }

        return [
            'status' => curl_getinfo($ch, CURLINFO_HTTP_CODE),
            'tempo' => round(curl_getinfo($ch, CURLINFO_TOTAL_TIME) * 1000),
            'dados' => json_decode($json, true),
        ];
    }
}
