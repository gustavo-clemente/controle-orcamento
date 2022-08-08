# API de Controle de orçamento 

### :construction: Em desenvolvimento :construction:

API criada em Symfony para controle de orçamento familiar.

Esse projeto está sendo desenvolvido como parte do #alurachallengebackend4

A principais funcionalidades são:

- `Cadastro de receitas`: os ganhos mensais de um usuário (salário, renda extra, etc.)
- `Cadastro de despesas`: os gastos do mês, sejam esses fixos ou eventuais.
- `Geração de relatórios`: resumo do mês com o total ganho e gasto, junto com o saldo final.

## Tecnologias utilizadas:
- Symfony: https://symfony.com/doc/current/index.html
- Doctrine: https://www.doctrine-project.org/projects/doctrine-orm/en/2.12/tutorials/getting-started.html

## Requisitos:

- PHP 8.1 ou superior
- Composer
- MYSQL 8.0 ou superior

## Instalação:

- Clone o repositório para sua máquina

```bash
git clone https://github.com/gustavo-clemente/controle-orcamento.git
```
- na pasta do projeto, instale as depedências pelo composer
```bash
composer install
```

- na raiz do projeto, crie um arquívo chamado .env.local e então configure sua conexão com o MYSQL da seguinte forma:
```.env
DATABASE_URL="mysql://{seuUsuario}:{suaSenha}@127.0.0.1:3306/{nomeDoBanco}?serverVersion=5.7&charset=utf8mb4"
```

- no terminal, execute o seguinte comando para montar a estrutura do banco de dados:
```bash
composer database:init
```

- caso queira subir um servidor de desenvolvimento para execução da API, utilize o seguinte comando:
```bash
composer server:up
```
após isso, será possível acessar a API por meio da url http://localhost:8080