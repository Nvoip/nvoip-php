# nvoip-php

[![CI](https://github.com/Nvoip/nvoip-php/actions/workflows/ci.yml/badge.svg)](https://github.com/Nvoip/nvoip-php/actions/workflows/ci.yml) [![Packagist](https://img.shields.io/packagist/v/nvoip/nvoip-php?style=flat-square)](https://packagist.org/packages/nvoip/nvoip-php) [![Packagist downloads](https://img.shields.io/packagist/dt/nvoip/nvoip-php?style=flat-square)](https://packagist.org/packages/nvoip/nvoip-php) [![Nvoip](https://img.shields.io/badge/Nvoip-site-00A3E0?style=flat-square)](https://www.nvoip.com.br/) [![API v3](https://img.shields.io/badge/API-v2-1F6FEB?style=flat-square)](https://www.nvoip.com.br/api/) [![Docs](https://img.shields.io/badge/docs-Apiary-6A737D?style=flat-square)](https://nvoip.docs.apiary.io/) [![Postman](https://img.shields.io/badge/Postman-workspace-FF6C37?style=flat-square)](https://nvoip-api.postman.co/workspace/e671d01f-168a-4c38-8d0e-c217229dd61a/team-quickstart) [![Stack](https://img.shields.io/badge/stack-PHP-777BB4?style=flat-square)](https://github.com/Nvoip/nvoip-api-examples) [![License: GPL-3.0](https://img.shields.io/badge/license-GPL--3.0-blue?style=flat-square)](LICENSE)

SDK e exemplos oficiais da [Nvoip](https://www.nvoip.com.br/) para integrar a API v3 com OAuth, chamadas, OTP, WhatsApp, SMS e saldo em PHP.

## Migração para v3

Esta é uma quebra de compatibilidade: use `createClientCredentialsToken()` e envie o access token RS256 em `Authorization: Bearer`. O SDK usa `https://api.nvoip.com.br/auth/oauth2/token`; não use `napikey`, password grant ou `/v3/oauth/token`. Para SMS de texto livre, valide antes a política e o template aprovado aplicáveis à sua conta.

Os tokens retornados devem ser consumidos somente pelo backend. Não os registre em logs, commits ou mensagens. Em OTP, `methods` é um objeto, por exemplo `['sms' => true]` para `phoneNumber`.

## O que tem aqui

- `src/NvoipClient.php`: cliente leve para a API v3
- `examples/`: exemplos separados por fluxo principal
- `Scripts/sender-sms.php`: endpoint PHP simples para disparo de SMS via query string

## Requisitos

- PHP 8.0+
- extensao `curl`

## Instalacao

```bash
composer require nvoip/nvoip-php
```

## Configuracao

Configure credenciais OAuth client credentials no ambiente do servidor:

```bash
export NVOIP_OAUTH_CLIENT_ID="seu_client_id"
export NVOIP_OAUTH_CLIENT_SECRET="seu_client_secret"
```

## Exemplos

- `php examples/create-client-credentials-token.php`
- `php examples/send-sms.php`
- `php examples/create-call.php`
- `php examples/send-otp.php`
- `php examples/check-otp.php`
- `php examples/list-whatsapp-templates.php`
- `php examples/send-whatsapp-template.php`

### Destinatário WhatsApp

O exemplo mantém `NVOIP_WA_DESTINATION` para telefone. Para o contrato tipado,
use `NVOIP_WA_RECIPIENT_TYPE=phone|bsuid|parent_bsuid` e
`NVOIP_WA_RECIPIENT_VALUE`, sem `destination`. BSUID é opaco; não use
`@username` nem o coloque em campo de telefone. Exemplos mascarados:
`US.MASKED_BSUID_001` e `PARENT.MASKED_BSUID_001`.

## Mini endpoint HTTP

O arquivo `Scripts/sender-sms.php` usa OAuth client credentials configurado no ambiente do servidor. Não aceite nem passe credenciais na query string.

Exemplo:

```text
https://seusite.exemplo/Scripts/sender-sms.php?numberPhone=11999999999&message=Mensagem%20de%20teste
```

## SDK web

Para o fluxo de popup com telefone e codigo, use o repositório `nvoip-web-sdk`. Este repo cobre o consumo server-side da API.

## Links oficiais

- [Site da Nvoip](https://www.nvoip.com.br/)
- [Documentação da API](https://nvoip.docs.apiary.io/)
- [Página da API](https://www.nvoip.com.br/api/)
- [Workspace Postman](https://nvoip-api.postman.co/workspace/e671d01f-168a-4c38-8d0e-c217229dd61a/team-quickstart)
- [Hub de exemplos](https://github.com/Nvoip/nvoip-api-examples)
