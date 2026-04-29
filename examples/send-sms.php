<?php

declare(strict_types=1);

require __DIR__ . '/../src/NvoipClient.php';

use Nvoip\NvoipClient;

$baseUrl = getenv('NVOIP_BASE_URL') ?: 'https://api.nvoip.com.br/v2';
$numbersip = getenv('NVOIP_NUMBERSIP') ?: '';
$userToken = getenv('NVOIP_USER_TOKEN') ?: '';
$target = getenv('NVOIP_TARGET_NUMBER') ?: '11999999999';
$message = getenv('NVOIP_SMS_MESSAGE') ?: 'Mensagem de teste Nvoip';

$client = new NvoipClient($baseUrl);
$oauth = $client->createAccessToken($numbersip, $userToken);
$response = $client->sendSms($target, $message, false, $oauth['access_token'] ?? '');

echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL;
