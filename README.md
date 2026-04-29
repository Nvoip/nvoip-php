# nvoip-php

Cliente PHP simples para a API v2 da Nvoip, com foco nos fluxos principais de autenticacao, ligacoes, OTP e WhatsApp.

## O que tem aqui

- `src/NvoipClient.php`: cliente leve para a API v2
- `examples/`: exemplos separados por fluxo principal
- `Scripts/sender-sms.php`: endpoint PHP simples para disparo de SMS via query string

## Requisitos

- PHP 8.0+
- extensao `curl`

## Configuracao

No painel da Nvoip, em `API`, voce encontra:

- `numbersip`
- `user-token`
- `napikey`

Tambem configure um destes formatos:

```bash
export NVOIP_OAUTH_CLIENT_ID="seu_client_id"
export NVOIP_OAUTH_CLIENT_SECRET="seu_client_secret"
```

## Exemplos

- `php examples/create-access-token.php`
- `php examples/send-sms.php`
- `php examples/create-call.php`
- `php examples/send-otp.php`
- `php examples/check-otp.php`
- `php examples/list-whatsapp-templates.php`
- `php examples/send-whatsapp-template.php`

## Mini endpoint HTTP

O arquivo `Scripts/sender-sms.php` mantem a ideia do script legado, mas ja usando OAuth da API v2.

Exemplo:

```text
https://seusite.exemplo/Scripts/sender-sms.php?numbersip=SEU_NUMBERSIP&user_token=SEU_USER_TOKEN&numberPhone=11999999999&message=Mensagem%20de%20teste
```

## SDK web

Para o fluxo de popup com telefone e codigo, use o repositório `nvoip-web-sdk`. Este repo cobre o consumo server-side da API.

## Documentacao oficial

- https://nvoip.docs.apiary.io/
- https://www.nvoip.com.br/api
