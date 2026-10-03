<?php

declare(strict_types=1);

require __DIR__ . '/../src/NvoipClient.php';

use Nvoip\NvoipClient;

$numberPhone = $_GET['numberPhone'] ?? $_GET['celular'] ?? '';
$message = $_GET['message'] ?? $_GET['msg'] ?? '';
$oauthClientId = getenv('NVOIP_OAUTH_CLIENT_ID') ?: null;
$oauthClientSecret = getenv('NVOIP_OAUTH_CLIENT_SECRET') ?: null;

if ($oauthClientId === null || $oauthClientSecret === null || $numberPhone === '' || $message === '') {
    http_response_code(400);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(
        [
            'error' => 'Missing required parameters.',
            'required' => ['NVOIP_OAUTH_CLIENT_ID', 'NVOIP_OAUTH_CLIENT_SECRET', 'numberPhone', 'message'],
        ],
        JSON_UNESCAPED_UNICODE
    );
    exit;
}

$client = new NvoipClient(
    getenv('NVOIP_BASE_URL') ?: 'https://api.nvoip.com.br/v3',
    $oauthClientId,
    $oauthClientSecret
);
$oauth = $client->createClientCredentialsToken();
$response = $client->sendSms($numberPhone, $message, false, $oauth['access_token'] ?? '');

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
