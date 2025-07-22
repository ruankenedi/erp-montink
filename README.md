# 🛒 Mini ERP - Controle de Pedidos, Produtos, Cupons e Estoque

Este é um sistema simples de ERP desenvolvido com **PHP puro + MySQL**, utilizando o padrão **MVC** e recursos úteis para a gestão de produtos, estoque, cupons e pedidos. Ideal para projetos de estudo ou lojas virtuais pequenas.

---

## ✅ Funcionalidades

- 📦 Cadastro de produtos com variações (ex: tamanho, cor)
- 🛒 Carrinho de compras com cálculo automático de frete:
  - R$20,00 para pedidos **abaixo de R$52,00**
  - R$15,00 entre **R$52,00 e R$166,59**
  - **Frete grátis** acima de **R$200,00**
- 🎟️ Aplicação de cupons com regras de valor mínimo e validade
- 📍 Consulta de endereço via **API ViaCEP**
- 📧 Envio automático de e-mail ao finalizar pedido (via **PHPMailer**)
- 🔄 Webhook para atualização e cancelamento de pedidos
- 🧱 Estrutura MVC simples com interface básica usando **Bootstrap**

---

## 💻 Tecnologias Utilizadas

- PHP (sem frameworks)
- MySQL
- HTML, CSS, JavaScript
- Bootstrap 5
- PHPMailer (envio de e-mails)
- Sessão em PHP (carrinho)
- API ViaCEP (consultas de endereço)

---

## 📁 Estrutura de Pastas

/config # Configurações gerais do sistema
/controllers # Controladores das funcionalidades (produtos, pedidos etc.)
/models # Classes de acesso ao banco de dados
/views # Telas e visualizações do sistema (HTML + PHP)
/assets # Arquivos estáticos (CSS, JS, imagens)

## 🗃️ Banco de Dados
Utilize o arquivo estrutura.sql disponível no repositório.

🚀 Como Rodar o Projeto

1. Clone o repositório ou extraia o .zip

2. Copie os arquivos para o diretório htdocs do seu XAMPP

3. Inicie o Apache e MySQL pelo XAMPP

4. Crie o banco de dados erp_montink e importe o SQL

5. Acesse no navegador:
http://localhost/erp-montink/

✉️ Configuração de Envio de E-mail (PHPMailer)
Para que o envio de e-mails funcione corretamente, siga estas instruções:

Acesse o arquivo de configuração do PHPMailer (ex: /config/email.php ou equivalente).

Altere os seguintes dados:

E-mail de origem (remetente)

E-mail de destino

Senha de app do Google

⚠️ Importante: O Google exige que você use uma senha de aplicativo para autenticação se a verificação em duas etapas estiver ativada.

Como gerar a senha de aplicativo no Gmail:

1. Acesse: https://myaccount.google.com/apppasswords

2. Faça login na conta Gmail usada para envio.

3. Escolha "Outro (nome personalizado)" e digite algo como "ERP Email".

4. Copie a senha gerada e use no seu script no lugar da senha da conta.

🔄 Teste de Webhook com Postman
Você pode testar o webhook de atualização de pedidos via Postman:

🧪 Como fazer:

1. Abra o Postman

2. Selecione o método GET

3. Use a URL:
http://localhost/erp-montink/webhook.php

4. Vá até a aba Body, selecione raw e o tipo JSON

5. Insira o seguinte exemplo de payload:
{
  "pedido_id": 2,
  "status": "cancelado"
}

6. Envie a requisição e o pedido com ID 2 será atualizado com o status "cancelado".
