# nvoip-php

[![Nvoip](https://img.shields.io/badge/Nvoip-site-00A3E0?style=flat-square)](https://www.nvoip.com.br/) [![API v2](https://img.shields.io/badge/API-v2-1F6FEB?style=flat-square)](https://www.nvoip.com.br/api/) [![Docs](https://img.shields.io/badge/docs-Apiary-6A737D?style=flat-square)](https://nvoip.docs.apiary.io/) [![Postman](https://img.shields.io/badge/Postman-workspace-FF6C37?style=flat-square)](https://nvoip-api.postman.co/workspace/e671d01f-168a-4c38-8d0e-c217229dd61a/team-quickstart) [![Stack](https://img.shields.io/badge/stack-PHP-777BB4?style=flat-square)](https://github.com/Nvoip/nvoip-api-examples) [![License: GPL-3.0](https://img.shields.io/badge/license-GPL--3.0-blue?style=flat-square)](LICENSE)

SDK e exemplos oficiais da [Nvoip](https://www.nvoip.com.br/) para integrar a API v2 com OAuth, chamadas, OTP, WhatsApp, SMS e saldo em PHP.

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

## Links oficiais

- [Site da Nvoip](https://www.nvoip.com.br/)
- [Documentação da API](https://nvoip.docs.apiary.io/)
- [Página da API](https://www.nvoip.com.br/api/)
- [Workspace Postman](https://nvoip-api.postman.co/workspace/e671d01f-168a-4c38-8d0e-c217229dd61a/team-quickstart)
- [Hub de exemplos](https://github.com/Nvoip/nvoip-api-examples)
