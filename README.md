# SalAveiro

Aplicação web de gestão de salinas, produção e encomendas, desenvolvida em PHP (MVC simples), MySQL/MariaDB e Bootstrap 5.

## Requisitos

- XAMPP (Apache + PHP 8.1 ou superior + MariaDB/MySQL)
- Extensões PHP: `pdo_mysql` e `curl`, ambas ativas por defeito no XAMPP

## Instalação

1. Copiar a pasta do projeto para `C:\xampp\htdocs\salaveiro`.
2. Importar a base de dados no phpMyAdmin com o ficheiro `db/bd_SalAveiro_AdonaiRodriguez_C27.sql`.
3. Copiar `.env.example` para `.env` e preencher os valores:

   ```
   DB_HOST=localhost
   DB_PORT=3306
   DB_NAME=bdsalaveiro_adonairodriguez_C27
   DB_USER=root
   DB_PASS=
   API_URL=http://192.168.1.91:5000/api/
   ```

4. Abrir `http://localhost/salaveiro`.

## Utilizadores de teste

A palavra-passe de todos os utilizadores é `12345678`.

| Utilizador | Perfil |
|---|---|
| admin | Administrador |
| operador1 | Operador |
| consultor1 | Consultor |
| consultor2 | Consultor (inativo: não consegue entrar) |

## Perfis e permissões

Todos os perfis podem consultar todas as páginas. Só podem criar, editar e apagar nas páginas abaixo:

| Perfil | Pode gerir |
|---|---|
| Administrador | Dados Principais (utilizadores, trabalhadores, salinas, tanques, categorias, produtos) |
| Operador | Salinas e Tanques, Produção e Comercial |
| Consultor | Nenhuma (só consulta) |

## Módulos

- **Dashboard:** totais da base de dados, utilizador autenticado e último acesso, e dados da API (meteorologia, preço de referência do sal e alertas ativos).
- **Dados Principais:** utilizadores, trabalhadores, salinas, tanques, categorias e produtos.
- **Produção:** registo de colheitas por tanque, com validação do estado e da capacidade do tanque.
- **Comercial:** encomendas (criação, itens, estados e cancelamento com reposição de stock), clientes e entradas de stock.
- **Dados Externos:** consulta dos endpoints da API, com tabela de resultados e JSON da resposta.

## Estrutura

```
index.php            Router (page=...&action=...) e controlo de permissões
.env                 Credenciais da base de dados e endereço da API (não vai para o git)
config/              Env.php, Database.php, Api.php
app/controllers/     Um controller por página
app/models/          Acesso à base de dados (PDO) e à API (cURL)
app/views/           Páginas e layout (header, footer, separadores)
app/helpers/         Permissões por perfil
assets/css/          Estilos
db/                  Script SQL da base de dados
docs/                Enunciado, diagramas e ecrãs de referência
```
