#! /bin/bash

# Instala as dependências do projeto
./run composer install

# Gera chaves públicas/privadas para uso na aplicação - API Lexik
./run bin/console lexik:jwt:generate-keypair

# Da permissão de escrita e leitura pasta /var/*
./run chmod 777 /var/ -R

# Cria o banco de dados
./run bin/console doctrine:database:create

# Monta schema no banco de dados
./run bin/console doctrine:schema:update --force

# Cria usuário administrador
./run bin/console security:create-admin admin admin@email.com admin

# Cria usuário padrão
./run bin/console security:create-user user user@email.com user

# Faz instalação dos assets
./run bin/console assets:install

# Limpa o cache do projeto
./run bin/console cache:clear
