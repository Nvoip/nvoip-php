<?php

declare(strict_types=1);

require __DIR__ . '/../src/NvoipClient.php';

use Nvoip\NvoipClient;

$client = new NvoipClient(getenv('NVOIP_BASE_URL') ?: 'https://api.nvoip.com.br/v2');

$response = $client->checkOtp(
    getenv('NVOIP_OTP_CODE') ?: '',
    getenv('NVOIP_OTP_KEY') ?: ''
);

echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL;
