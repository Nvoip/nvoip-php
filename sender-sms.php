<!--
Nvoip

Copyright (C) 2020 Nvoip Plataforma Telefonia Ltda
Leandro Campos <https://www.linkedin.com/in/leandro-campos/>
License https://www.gnu.org/licenses/gpl-3.0.html

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see <https://www.gnu.org/licenses/>.
-->
<html>
 <head>
  <title>SMS Trigger by Nvoip API</title>
 </head>
 <body>
 <?php
-------------------------------------------------------------------------------------
  
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://api.nvoip.com.br/v1/sms");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);

curl_setopt($ch, CURLOPT_POST, TRUE);

#Token de autenticação. Você consegue este token criando sua conta gratuita em https://www.nvoip.com.br
#Token Authentication. You can get this token by creating your free account at https://www.nvoip.com.br
$token_auth = $_GET["token_auth"];

#Número de celular que irá receber o SMS (No momento, só é possível enviar SMS para números Brasileiros). Não colocar DDI, apenas DDD + número. Exemplo: 11911112222.
#Mobile number that will receive SMS (Currently, it is only possible to send SMS to Brazilian numbers). Do not put DDI, just DDD + number. Example: 11911112222.
$celular = $_GET["celular"];

#Mensagem a ser enviada. Lembre-se de não utilizar acentos ou caracteres especiais e limite-se a 140 caracteres.
#Message to send. Remember not to use accents or special characters and limit yourself to 140 characters.
$msg = $_GET["msg"];

curl_setopt($ch, CURLOPT_POSTFIELDS, "{
  \"celular\": \"$celular\",
  \"msg\": \"$msg\"
}");

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  "Content-Type: application/json",
  "token_auth: $token_auth"
));

$response = curl_exec($ch);
curl_close($ch);

var_dump($response);


?>
 </body>
</html>
