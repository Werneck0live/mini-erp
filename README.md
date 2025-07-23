## Sistema de Controle de Pedidos, Produtos, Estoque e Cupons

Este projeto é um mini ERP desenvolvido para atender às instruções do teste para vaga de Desenvolvedor Backend na empresa Montink. O sistema contempla funcionalidades de cadastro de produtos com variações, gerenciamento de estoque, carrinho de compras com controle de sessão, cupons de desconto, frete automatizado, integração via webhook e envio de e-mails.

---

## Observações: Requisitos Técnicos

### 1) Dependência de Apache com `.htaccess`

O sistema foi projetado para funcionar sob o servidor **Apache**, utilizando os recursos de URL amigável via `.htaccess`. Portanto, é necessário utilizar o Apache como servidor web para o correto funcionamento das rotas e redirecionamentos. 

Caso opte utilizar o **Docker** , logo abaixo há as instruções para rodar o projeto utilizando a receita que está na pasta `/docker` do projeto.

---

### 2) Utilização do `.env`

Todas as credenciais sensíveis, como acesso ao banco de dados e configurações de envio de e-mail, são lidas do **arquivo `.env`** localizado em:

```

app/.env

````

> ⚠️ Por boas práticas de segurança, esse arquivo **não foi versionado** no repositório. Por isso, é necessário que você crie o arquivo e o mova para a pasta **app/** .

#### Exemplo de `.env`:

```env
# SMTP
SMTP_HOST='seuhostsmtp.soumexemplo.com'
SMTP_PORT='587'
SMTP_CHARTSET='UTF-8'
SMTP_USER='seuemail@dominio.com'
SMTP_PASS='suasenha'
SMTP_FROM_EMAIL='seuemail@dominio.com'
SMTP_FROM_NAME='Loja Mini - ERP'
SMTP_AUTH=true

# MYSQL
MYSQL_PORT='3306'
MYSQL_DATABASE='erp'
MYSQL_USER='mysql_user'
MYSQL_PASSWORD='mysql123'
MYSQL_HOST='mysql'
````

---

## Inicializar o ambiente

### Utilização COM Docker

Este projeto possui uma estrutura simples e organizada para facilitar o uso com Docker:

```
/             -   Raiz do projeto

├── app/      -   Código-fonte do sistema

└── docker/   -   Arquivos relacionados ao Docker (docker-compose.yml, scripts, etc.)
```


Para iniciar os containers do projeto, siga os passos abaixo:

1. Certifique-se de estar no diretório raiz do projeto.

2. Execute o comando abaixo para subir os containers e rodar o projeto:


```bash
sudo docker compose -f docker/docker-compose.yml up -d
```
Caso queria parar os serviços do projeto e "derrubar" os containers, utilize:

```bash
sudo docker compose -f docker/docker-compose.yml down
```



3. Conforme está no docker-compose.yml, o sistema está exposto na porta `"8000"`. Por isso, para acessá-lo, basta acessar a url *http://localhost:8000/* .

#### Acesso ao Banco de Dados via Docker

O serviço MySQL expõe a porta `33100` no host, portanto, caso queira acessá-lo por uma IDE (Mysql Workbench, HeideSQL), basta utilizar os seguintes parâmetros:

* **Host:** `localhost`
* **Porta:** `33100`
* **Usuário** `mysql_user`
* **Senha:** `mysql123`

---

### Utilização SEM Docker

Caso deseje utilizar o sistema fora do Docker:

* A aplicação está localizada na pasta: `/app`. Por isso você deve configurar seu servidor web (Apache) para apontar o diretório raiz do site para essa pasta.
* Com sua instância do Mysql criada, basta informar as credênciais no arquivo .env normalmente, pois a configuração é lida pelo arquivo `/app/config/databases/mysql_config.php`.
* **Scripts SQL**:

  * Criação da estrutura:
    `/docker/ini/mysql/01-struct_schema_tables_mini_erp.sql`
  * População de dados (opcional):
    `/docker/ini/mysql/02-inserts_tables_mini_erp.sql`

> ⚠️ Caso deseje iniciar o sistema do zero, **ignore o segundo script** (`02-inserts...`), que contém apenas dados de exemplo.

---

## Outras informações

### Convenções de Código

Em geral, o idioma padrão para sistemas e ambientes de desenvolvimento é o **inglês**. No entando, foi feita a escolha de utilizar **nomes de campos e funções em português**, visando a legibilidade e melhor entendimento de regras de negócio para avaliadores e terceiros que estão envolvidos neste processo seletivo.


---

### Envio de E-mails

O sistema envia e-mails ao finalizar um pedido e quando é utilizado o Webhook para atualização de pedidos. Desta forma, a biblioteca **PHPMailer** foi utilizada no projeto para gerenciar a função de envio de e-mail. A mesma está na pasta:

```
/libraries/PHPMailer/
```

Não é necessário instalar dependências externas via Composer.

### Configuração com Gmail

Caso deseje utilizar sua conta do Gmail:

Além de utilizar inserir as configurações do servidor smtp do Gmail no arquivo .env, é necessário inserir `sua senha para login em Apps`. Basta seguir os passos:
1. Acesse: [https://myaccount.google.com/security](https://myaccount.google.com/security)
2. Ative a verificação em duas etapas
3. Vá em **Senhas de app**
4. Gere uma senha para "Mail" e "Outro" (nomeie como "PHPMailer")
5. Use a senha gerada no `.env`

### Outra alternativa para testes de envio de e-mail: MailTrap

Caso prefira um ambiente seguro para testes de e-mail, utilize:
[https://mailtrap.io/](https://mailtrap.io/). O mesmo possui fácil configuração smtp e visualização dos e-mail que são enviados.

---

### Proteções de Sessão e Checkout

O sistema possui proteções inteligentes, como:

* Se o usuário sair do checkout e um produto for editado ou removido, ele será automaticamente **atualizado no carrinho**.
* Caso o mesmo produto seja adicionado mais de uma vez em momentos distintos, ele será **consolidado** em um único item com a **quantidade atualizada**.

---

### Webhook de Atualização de Pedido

O sistema possui um webhook simples para atualização de status de pedido.

#### Endpoint:

```http
POST http://localhost:8000/webhook/atualizar
```

#### Exemplo com `curl`:

```bash
curl -X POST http://localhost:8000/webhook/atualizar \
  -H "Content-Type: application/json" \
  -d '{"id": 18, "status": "Em rota"}'
```

#### Observações:

* Para **cancelar** um pedido, informe o status como:

  * `"inativo"` ou `"cancelado"`
* Embora em sistemas reais seja comum utilizar **flags inteiros** para status (por exemplo, `0 = cancelado`, `1 = ativo`, etc), neste projeto optou-se por usar **valores por extenso** para facilitar o entendimento do fluxo de atualização.

---

## Funcionalidades Atendidas

* Cadastro e edição de produtos com variações e controle de estoque
* Carrinho com controle de sessão
* Políticas de frete conforme o subtotal
* Aplicação e gerenciamento de cupons com validade e valor mínimo
* Verificação de endereço via [ViaCEP](https://viacep.com.br/)
* Envio de e-mail de confirmação de pedido
* Webhook de atualização/cancelamento de pedidos

---

## Conclusão


Este sistema cumpre todos os requisitos descritos no desafio técnico proposto. O foco esteve na clareza do código, boas práticas e previsibilidade de comportamento, com atenção especial a pontos críticos como controle de sessão, regras de negócio e modularização.