<?php
// Lê o ficheiro .env (CHAVE=valor) da raiz do projeto, uma única vez.
class Env
{
    private static $valores = null;

    public static function get($chave, $padrao = '')
    {
        if (self::$valores === null) {
            self::$valores = [];
            $ficheiro = __DIR__ . '/../.env';

            if (file_exists($ficheiro)) {
                foreach (file($ficheiro, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $linha) {
                    $linha = trim($linha);
                    if ($linha === '' || $linha[0] === '#' || !str_contains($linha, '=')) {
                        continue;
                    }
                    [$k, $v] = explode('=', $linha, 2);
                    self::$valores[trim($k)] = trim(trim($v), '"\'');
                }
            }
        }

        return self::$valores[$chave] ?? $padrao;
    }
}
