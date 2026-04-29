# nvoip-php

Cliente PHP simples para a API v2 da Nvoip, com foco em exemplos prontos para integracoes de voz, SMS, OTP e WhatsApp.

## O que tem aqui

- `src/NvoipClient.php`: cliente leve para a API v2
- `examples/send-sms.php`: exemplo minimo de envio de SMS
- `Scripts/sender-sms.php`: endpoint PHP simples para disparo de SMS via query string

## Requisitos

- PHP 8.0+
- extensao `curl`

## Credenciais necessarias

No painel da Nvoip, em `API`, voce encontra:

- `numbersip`
- `user-token`
- `napikey`

Para integracoes comerciais, prefira OAuth.

## Instalacao

```bash
composer dump-autoload
cp .env.example .env
```

## Uso rapido

Defina as variaveis:

```bash
export NVOIP_NUMBERSIP="seu_numbersip"
export NVOIP_USER_TOKEN="seu_user_token"
export NVOIP_TARGET_NUMBER="11999999999"
export NVOIP_SMS_MESSAGE="Mensagem de teste Nvoip"
```

Rode o exemplo:

```bash
php examples/send-sms.php
```

## Mini endpoint HTTP

O arquivo `Scripts/sender-sms.php` mantem a ideia do script legado, mas ja usando OAuth da API v2.

Exemplo:

```text
https://seusite.exemplo/Scripts/sender-sms.php?numbersip=SEU_NUMBERSIP&user_token=SEU_USER_TOKEN&numberPhone=11999999999&message=Mensagem%20de%20teste
```

## Operacoes cobertas pelo cliente

- gerar `access_token`
- renovar `access_token`
- consultar saldo
- enviar SMS
- realizar chamada
- consultar chamada
- enviar OTP
- listar templates de WhatsApp
- enviar template de WhatsApp

## Documentacao oficial

- https://nvoip.docs.apiary.io/
- https://www.nvoip.com.br/api
