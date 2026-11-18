<?php
// Devolve true se o perfil autenticado pode gerir (criar, editar, apagar) na página indicada.
// Todos os perfis podem consultar todas as páginas.
function podeGerir($pagina)
{
    // páginas com outro nome que abrem a mesma coisa
    $aliases = ['dados' => 'utilizadores', 'comercial' => 'encomendas'];
    $pagina = $aliases[$pagina] ?? $pagina;

    $permissoes = [
        'Administrador' => ['utilizadores', 'trabalhadores', 'salinas', 'tanques', 'categorias', 'produtos'],
        'Operador'      => ['salinas', 'tanques', 'producao', 'encomendas', 'clientes', 'stock'],
        'Consultor'     => [],
    ];

    $perfil = $_SESSION['perfil'] ?? '';
    return in_array($pagina, $permissoes[$perfil] ?? []);
}
