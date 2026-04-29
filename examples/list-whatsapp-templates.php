<?php

declare(strict_types=1);

require __DIR__ . '/../src/NvoipClient.php';

use Nvoip\NvoipClient;

$client = new NvoipClient(
    getenv('NVOIP_BASE_URL') ?: 'https://api.nvoip.com.br/v2',
    getenv('NVOIP_OAUTH_BASIC_AUTH') ?: null,
    getenv('NVOIP_OAUTH_CLIENT_ID') ?: null,
    getenv('NVOIP_OAUTH_CLIENT_SECRET') ?: null
);

$oauth = $client->createAccessToken(
    getenv('NVOIP_NUMBERSIP') ?: '',
    getenv('NVOIP_USER_TOKEN') ?: ''
);

$response = $client->listWhatsAppTemplates($oauth['access_token'] ?? '');

echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL;
