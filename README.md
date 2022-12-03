<h1>Projeto Web-Servidor : Sistema de Aluguel de Carros Parte 3 - API</h1>

## Desenvolvido com

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Integrantes e Suas Contribuições

<ul>
    <li>Caio: Desenvolvimento das rotas testes em Postman, criação dos métodos de validação;</li>
    <li>Maria Isabel: Adaptação dos Controllers do sistema para retornarem respostas em formato JSON, criação de métodos para manipulação de Tokens;</li>
    <li>Nauder: Migração do sistema para o Framework Laravel (Migrations, Validate, etc.), modificações nas rotas para servirem em uma api com Sanctum.</li>
</ul>

## Documentação de instalação e configuração do software

<p>Tecnologias utilizadas e recomendadas para reprodução: </p>
<ul>
    <li><a href="https://www.apachefriends.org/download">XAMPP</a>;</li>
    <li>PHP >= 8.1.6;</li>
    <li><a href="https://getcomposer.org/download">Composer</a>;</li>
    <li><a href="https://www.postgresql.org/download">PostgreSQL</a>;</li>
    <li><a href="https://www.postman.com">Postman</a>.</li>
</ul>

<p>Para inicar o projeto em um localhost, após a instalação de softwares necessários, execute os seguintes comandos em ordem no terminal na pasta root do sistema:

```
composer install
```
```
php artisan migrate
```
```
php artisan serve
```

<p>Em seguida o servidor já estará disponivel para uso. É importante mencionar que para acessar funcionalidades de admin em um novo local é necessário remover a autorização Sanctum na rota de criar admin, pois a mesma só pode ser acessada por admins já existentes.</p>
<p>Rotas teste em formato do Software Postman foram fornecidas para facilitar o acesso ao sistema, todas as rotas seguem a mesma lógica e cumprem com o mínimo das boas práticas de uma API RESTful.
<p>Orientações básicas para uso de rotas: <p>
<ul>
    <li>Todas as rotas de consulta podem receber qualquer argumento relevante para filtragem de data, simulando um comando SQL "WHERE", EX: "?nome=gol?ano=2012" funcionaria como o comando SQL "WHERE nome = 'gol' AND ano = 2012";</li>
    <li>Rotas de consulta de admin podem utilizar "||" para representar uma expressão SQL "OR" para argumentos informados. EX: "?nome=gol||onyx" funcionaria como o comando "WHERE nome IN('gol', 'onyx')";</li>
    <li>A lógica de validação de argumentos para rotas, bem como os argumentos possíveis, seguem a lógica de acesso definida nos arquivos Model do sistema além de validações para campos especiais como CPF, CNPJ, etc.</li>
</ul>

## Licença

O projeto desenvolvido pode ser redistribuido através da [licença MIT](https://opensource.org/licenses/MIT).
