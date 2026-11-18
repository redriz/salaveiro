<?php
require_once __DIR__ . '/Env.php';

class ApiConfig
{
    // endereço base da API, sempre terminado em "/"
    public static function baseUrl()
    {
        return rtrim(Env::get('API_URL'), '/') . '/';
    }
}
