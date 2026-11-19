<?php
// Devolve true se o perfil autenticado pode gerir (criar, editar, apagar) na página indicada.
// Todos os perfis podem consultar todas as páginas.
function podeGerir($pagina)
{
    if (($_SESSION['perfil'] ?? '') === 'Administrador') {
        return true;
    }

    // páginas com outro nome que abrem a mesma coisa
    $aliases = ['dados' => 'utilizadores', 'comercial' => 'encomendas'];
    $pagina = $aliases[$pagina] ?? $pagina;

    $permissoes = [
        'Operador'      => ['salinas', 'tanques', 'producao', 'encomendas', 'clientes', 'stock'],
        'Consultor'     => [],
    ];

    $perfil = $_SESSION['perfil'] ?? '';
    return in_array($pagina, $permissoes[$perfil] ?? []);
}
