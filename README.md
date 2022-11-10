# Gerador de Sistemas - Projeto Base PHP

[![php version](https://img.shields.io/badge/php-v8.1-blue?style=flat&logo=php)](php/composer.json#L6)
[![build Status](https://github.com/ludofleury/blackflag/workflows/ci/badge.svg?branch=main)](https://github.com/ludofleury/blackflag/actions)
[![codecov](https://codecov.io/gh/ludofleury/blackflag/branch/main/graph/badge.svg?token=u7d7nhlwb8)](https://codecov.io/gh/ludofleury/blackflag)
[![phpstan](https://img.shields.io/badge/sonata_admin-level%208-brightgreen.svg?style=flat)](CONTRIBUTING.md#phpstan)
[![psalm](https://img.shields.io/badge/psalm-level%202-brightgreen.svg?style=flat)](CONTRIBUTING.md#psalm)
[![mutation testing](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fludofleury%2Fblackflag%2Fmain)](https://dashboard.stryker-mutator.io/reports/github.com/ludofleury/blackflag/main)


Provides a framework for implementing a complete web project

* PSR-1 & PSR-2
* Symfony standard
* Symfony naming conventions]
* framework:
  symfony 6.0
  Sonata Admin
  php 8.0



configuration:

composer install - instala dependencias

bin/console doctrine:schema:update --force - cria estrutura banco de dados

lexik:jwt:generate-keypair - gera chave publica e privada jwt


symfony server:start - inicia servidor









git reset --hard

Commands ->

bin/console create:database
create user in database and set password: bin/console security:hash-password




Strongly inspired by [OpenSky Symfony2 coding standard](https://github.com/opensky/Symfony2-coding-standard) (forked InterfaceSuffixSniff).
Yet, this ruleset rely on CodeSniffer PSR-1 & 2 sniffs and add Symfony standard & naming conventions. It's also allow chained calls (fluent interface).


## Requirements
1. Install docker:

        docker
2. Install docker-compose

        docker-compose


## Installation

1. Construa a imagem da aplicação com o seguinte comando:

        docker compose build app

2. Execute o ambiente em segundo plano com:

        docker compose up -d
3. Execute o ambiente em segundo plano com:

         docker compose exec app 

4. Copy, symlink or check out this repo to a folder called Symfony inside the
   phpcs `Standards` directory:

        cd /path/to/pear/PHP/CodeSniffer/Standards
        git clone git://github.com/ludofleury/symfony-coding-standard.git Symfony



## Pragmatic & opinionated Customisations

### Allows fluent-interface chained calls syntax

```php
<?php

    $this
        ->getFoo()
            ->getBar()
            ->getBar()
    ; // This is allowed

    $this->getFoo()  ; // This is a violation

?>
```

## Known limitations

* "Exception" naming convention isn't enforced (Symfony require Exception suffix)
* PHPDoc blocks for all classes, methods, and functions isn't enforced at the moment


## Filtrando, Classificando e Paginando

### Filtrando

Os clientes precisam enviar a solicitação na própria sintaxe de string de consulta do PHP, que difere do formato de 
string de consulta CGI padrão. Abaixo está uma lista completa dos operadores suportados.


| Operador         | Descrição       | Exemplo
|------------------|-----------------| -----------------
| `igual`          | Igualdade       | `name[igual]=Jimothy`
| `diferente`      | Diferença       | `status[diferente]=backlog`
| `maior`          | Maior que       | `price[maior]=10`
| `maior_ou_igual` | Maior ou igual  | `price[maior_ou_igual]=10`
| `menor`          | Menor que       | `stock[menor]=100`
| `menor_ou_igual` | Menor ou igual  | `stock[menor_ou_igual]=100`
| `dentro`         | ~~Dentro~~      | `id[dentro][]=1&id[dentro][]=2`
| `nao_dentro`     | ~~Não dentro~~  | `roles[nao_dentro][]=ROLE_ADMIN`
| `nulo`           | É nulo          | `subscribedAt[nulo]`
| `nao_nulo`       | Não é nulo      | `subscribedAt[nao_nulo]`
| `comeca_com`     | Começa com      | `name[comeca_com]=a`
| `termina_com`    | Termina com     | `email[termina_com]=@gmail.com`
| `contem`         | Contém          | `name[contem]=d`


<br>
<br>

| Operador        | Descrição      | Exemplo
| --------------- |----------------| -----------------
| `eq`            | Igualdade      | `name[eq]=Jimothy`
| `neq`           | Diferença      | `status[neq]=backlog`
| `gt`            | Maior que      | `price[gt]=10`
| `gte`           | Maior ou igual | `price[gte]=10`
| `lt`            | Menor que      | `stock[lt]=100`
| `lte`           | Menor ou igual | `stock[lte]=100`
| `in`            | ~~Dentro~~         | `id[in][]=1&id[in][]=2`
| `not_in`        | ~~Não dentro~~     | `roles[not_in][]=ROLE_ADMIN`
| `is_null`       | É nulo         | `subscribedAt[is_null]`
| `is_not_null`   | Não é nulo     | `subscribedAt[is_not_null]`
| `starts_with`   | Começa com     | `name[starts_with]=a`
| `ends_with`     | Termina com    | `email[ends_with]=@gmail.com`
| `contains`      | Contém         | `name[containts]=d`

### Classificando

| Operador      | Descrição      | Exemplo
|---------------|----------------| -----------------
| `ordenar_por` | Ordenar Crescente       | `id[orderBy]=asc`
| `ordenar_por`     | Ordenar Decrescente | `nome[orderBy]=desc`

A Classificação é aplicada por meio da chave de string de consulta `orderBy`, 
é aplicado através da seguinte sintaxe: `fieldName[orderBy]=direction` onde o fieldName é o campo a ser classificado e a direção deve ser `asc` Crescente ou `desc` Decrescente.

A chave `orderBy` pode ser usada várias vezes e permiti a classificação por vários campos.
Por exemplo: `id[orderBy]=asc&nome[orderBy]=desc`

### Paginando
| Operador        | Descrição         | Exemplo
|-----------------|-------------------| -----------------
| `pagina`        | página            | `pagina=1`
| `paginaTamanho` | tamanho da página | `paginaTamanho=10`

A Paginação é aplicada por meio das chaves de string de consulta `pagina` e `paginaTamanho`.
A chave `pagina` é aplicada através da seguinte sintaxe: `pagina=numero` onde o number deve ser um número inteiro representando a página a ser consultada.
A chave `paginaTamanho` deve ser aplicada através da seguinte sintaxe: `paginaTamanho=numero` onde o number deve ser um número inteiro representado a tamanho de itens por página.

Default value `pagina` = 1

Default value `paginaTamanho` = 10

## Guide

dsadsd

## Contributing

If you do contribute code to these sniffs, please make sure it conforms to the PEAR coding standard and that the unit tests still pass.

To check the coding standard, run from the Symfony-coding-standard source root:

        phpcs --ignore=Tests --standard=PEAR . -n

The unit-tests are run from within the PHP_CodeSniffer directory

* get the [CodeSniffer repository](https://github.com/squizlabs/PHP_CodeSniffer)
* symlink, copy or clone this repository at CodeSniffer/Standard/Symfony
* from the CodeSniffer repository root run `phpunit --filter Symfony_ tests/AllTests.php`

## Credit

[OpenSky](https://github.com/opensky) for the [Symfony2 coding standard](https://github.com/opensky/Symfony2-coding-standard)

## Issue

If you spot any missing standard/conventions and don't want to contribute, please open an issue. It will be at least added to this readme.

## Licence

Copyright (c) 2013 Ludovic Fleury

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is furnished
to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

