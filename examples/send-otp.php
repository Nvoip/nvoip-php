<?php

declare(strict_types=1);

require __DIR__ . '/../src/NvoipClient.php';

use Nvoip\NvoipClient;

$client = new NvoipClient(getenv('NVOIP_BASE_URL') ?: 'https://api.nvoip.com.br/v2');

$response = $client->sendOtp(
    [
        'sms' => getenv('NVOIP_TARGET_NUMBER') ?: '11999999999',
    ],
    null,
    getenv('NVOIP_NAPIKEY') ?: null
);

echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL;
