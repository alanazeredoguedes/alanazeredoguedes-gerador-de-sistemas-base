#! /bin/bash

# Instala as dependências do projeto
./run composer install

# Cria o banco de dados
./run bin/console doctrine:database:create

# Monta schema no banco de dados
./run bin/console doctrine:schema:update --force

# Cria usuário administrador
./run bin/console security:create-admin admin admin@email.com admin

# Cria usuário padrão
./run bin/console security:create-user user user@email.com user

# Faz instalação dos assets
./run bin/console assets:install --symlink

# Limpa o cache do projeto
./run bin/console cache:clear
