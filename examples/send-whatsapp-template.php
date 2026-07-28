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
    'instance' => getenv('NVOIP_WA_INSTANCE') ?: '',
    'language' => getenv('NVOIP_WA_LANGUAGE') ?: 'pt_BR',
];

$recipientType = strtolower(trim(getenv('NVOIP_WA_RECIPIENT_TYPE') ?: ''));
$recipientValue = trim(getenv('NVOIP_WA_RECIPIENT_VALUE') ?: '');
if ($recipientType !== '') {
    if (!in_array($recipientType, ['phone', 'bsuid', 'parent_bsuid'], true) || $recipientValue === '') {
        throw new InvalidArgumentException('NVOIP_WA_RECIPIENT_TYPE must be phone, bsuid or parent_bsuid and requires NVOIP_WA_RECIPIENT_VALUE');
    }
    if (str_starts_with($recipientValue, '@')) {
        throw new InvalidArgumentException('@username is not a WhatsApp recipient; use a BSUID or parent BSUID');
    }
    if ($recipientType === 'phone' && !preg_match('/^\+?[0-9]{8,20}$/', $recipientValue)) {
        throw new InvalidArgumentException('A phone recipient must contain only an optional leading + and 8 to 20 digits');
    }
    if ($recipientType !== 'phone' && (preg_match('/\s/', $recipientValue) || strlen($recipientValue) > 256)) {
        throw new InvalidArgumentException('A BSUID must be an opaque value without whitespace (maximum 256 characters)');
    }
    $payload['recipient'] = ['type' => $recipientType, 'value' => $recipientValue];
} else {
    $destination = getenv('NVOIP_WA_DESTINATION') ?: (getenv('NVOIP_TARGET_NUMBER') ?: '');
    if (!preg_match('/^\+?[0-9]{8,20}$/', $destination)) {
        throw new InvalidArgumentException('NVOIP_WA_DESTINATION must be a phone number; use recipient for BSUID');
    }
    $payload['destination'] = $destination;
}

$bodyVariables = json_decode(getenv('NVOIP_WA_BODY_VARIABLES') ?: '[]', true);
$headerVariables = json_decode(getenv('NVOIP_WA_HEADER_VARIABLES') ?: '[]', true);

if (is_array($bodyVariables) && $bodyVariables !== []) {
    $payload['bodyVariables'] = $bodyVariables;
}

if (is_array($headerVariables) && $headerVariables !== []) {
    $payload['headerVariables'] = $headerVariables;
}

if ((getenv('NVOIP_WA_TO_FLOW') ?: 'false') === 'true') {
    if (in_array($recipientType, ['bsuid', 'parent_bsuid'], true)) {
        throw new InvalidArgumentException('WhatsApp Flow and attendance require a phone recipient');
    }
    $payload['functions'] = ['to_flow' => true];
}

$response = $client->sendWhatsAppTemplate($payload, $oauth['access_token'] ?? '');

echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL;
