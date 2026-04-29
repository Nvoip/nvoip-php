<?php

declare(strict_types=1);

require __DIR__ . '/../src/NvoipClient.php';

use Nvoip\NvoipClient;

$client = new NvoipClient(
    getenv('NVOIP_BASE_URL') ?: 'https://api.nvoip.com.br/v2',
    getenv('NVOIP_OAUTH_CLIENT_ID') ?: null,
    getenv('NVOIP_OAUTH_CLIENT_SECRET') ?: null
);

$oauth = $client->createAccessToken(
    getenv('NVOIP_NUMBERSIP') ?: '',
    getenv('NVOIP_USER_TOKEN') ?: ''
);

$payload = [
    'idTemplate' => getenv('NVOIP_WA_TEMPLATE_ID') ?: '',
    'destination' => getenv('NVOIP_WA_DESTINATION') ?: (getenv('NVOIP_TARGET_NUMBER') ?: '11999999999'),
    'instance' => getenv('NVOIP_WA_INSTANCE') ?: '',
    'language' => getenv('NVOIP_WA_LANGUAGE') ?: 'pt_BR',
];

$bodyVariables = json_decode(getenv('NVOIP_WA_BODY_VARIABLES') ?: '[]', true);
$headerVariables = json_decode(getenv('NVOIP_WA_HEADER_VARIABLES') ?: '[]', true);

if (is_array($bodyVariables) && $bodyVariables !== []) {
    $payload['bodyVariables'] = $bodyVariables;
}

if (is_array($headerVariables) && $headerVariables !== []) {
    $payload['headerVariables'] = $headerVariables;
}

if ((getenv('NVOIP_WA_TO_FLOW') ?: 'false') === 'true') {
    $payload['functions'] = ['to_flow' => true];
}

$response = $client->sendWhatsAppTemplate($payload, $oauth['access_token'] ?? '');

echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL;
