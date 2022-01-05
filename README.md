## Projeto Cofre de Senhas

Projeto utilizado para estudar Laravel 8 + VueJS 3

## Instalação do projeto

#### Pré requisitos
- Docker
- Node

Faça o clone do projeto e acesse o diretório cofre-senhas

#### API
Acesse o diretório "api" e execute o comando abaixo para instalar as dependências do projeto
```
composer install --ignore-platform-reqs
```
Duplique o arquivo .env.example e renomeie para .env <br>
Preencha a chave JWT_SECRET no .env com uma chave aleatória de sua escolha<br>

Execute o seguinte comando para iniciar a API
```
composer serve
```

Em seguida, crie um banco de dados chamado "api" por algum SGDB, eu utilizei o Dbeaver e rode as migrations com o comando
```
./vendor/bin/sail artisan migrate
```

#### Frontend
Acesse o diretório "frontend" e execute o comando abaixo para instalar as dependências do projeto
```
npm install
```

Execute o seguinte comando para iniciar o Frontend
```
npm run serve
```
