<?php

declare(strict_types=1);

require __DIR__ . '/../src/NvoipClient.php';

use Nvoip\NvoipClient;

$client = new NvoipClient(getenv('NVOIP_BASE_URL') ?: 'https://api.nvoip.com.br/v3', getenv('NVOIP_OAUTH_CLIENT_ID') ?: null, getenv('NVOIP_OAUTH_CLIENT_SECRET') ?: null);
$oauth = $client->createClientCredentialsToken();

$response = $client->sendOtp(
    [
        'phoneNumber' => getenv('NVOIP_TARGET_NUMBER') ?: '11999999999',
        'methods' => ['sms' => true],
    ],
    $oauth['access_token'] ?? ''
);

echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL;
