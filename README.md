# nvoip-php
Scripts e bibliotecas para a linguagem PHP para uso com a API da Nvoip.

## Intruções
- Para usar os scripts, é necessário ter uma conta e um token válido na Nvoip. Você pode criar sua conta gratuitamente em https://www.nvoip.com.br
- Sinta-se livre para criar e contribuir com os códigos deste repositório. Também fique a vontade para reportar bugs relacionados ao uso da API com a linguagem PHP.
- Acesse https://www.nvoip.com.br/api para acessar a documentação da nossa API.

## Scripts by Nvoip:
### Disparo de SMS - sender-sms.php
Este script permite você enviar SMS de 2 formas:
1. Copiando o código para o seu site e alterando as variáveis $celular, $token_auth e $msg.

2. Usando como um mini servidor para requisições HTTP.
Neste caso, você irá passar os parâmetros da seguinte forma:
https://{urldoseusite/sender-sms.php}/?auth_token={token}&celular={celular}&msg={msg}

# English Version
PHP language scripts and libraries for use with the Nvoip API.

## Instructions
- To use the scripts, you must have a valid Nvoip account and token. You can create your account for free at https://www.nvoip.com.br
- Feel free to create and contribute code from this repository. Also feel free to report bugs related to the use of the API with the PHP language.
- Visit https://www.nvoip.com.br/api to access our API documentation.

## Scripts by Nvoip:
### SMS Trigger - sender-sms.php
This script allows you to send SMS with 2 methods:
1. Copying the code to your site and changing the $mobile, $token_auth and $msg variables.

2. Using as a mini server for HTTP requests.
In this case, you will pass the parameters as follows:
https://{urldoseusite/sender-sms.php}/?auth_token={token}&mobile={mobile}&msg={msg}
