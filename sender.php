<html>
 <head>
  <title>SMS Trigger by Nvoip API</title>
 </head>
 <body>
 <?php

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
