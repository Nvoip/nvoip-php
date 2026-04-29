<?php

declare(strict_types=1);

require __DIR__ . '/../src/NvoipClient.php';

use Nvoip\NvoipClient;

$numbersip = $_GET['numbersip'] ?? getenv('NVOIP_NUMBERSIP') ?: '';
$userToken = $_GET['user_token'] ?? getenv('NVOIP_USER_TOKEN') ?: '';
$numberPhone = $_GET['numberPhone'] ?? $_GET['celular'] ?? '';
$message = $_GET['message'] ?? $_GET['msg'] ?? '';

if ($numbersip === '' || $userToken === '' || $numberPhone === '' || $message === '') {
    http_response_code(400);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(
        [
            'error' => 'Missing required parameters.',
            'required' => ['numbersip', 'user_token', 'numberPhone', 'message'],
        ],
        JSON_UNESCAPED_UNICODE
    );
    exit;
}

$client = new NvoipClient(getenv('NVOIP_BASE_URL') ?: 'https://api.nvoip.com.br/v2');
$oauth = $client->createAccessToken($numbersip, $userToken);
$response = $client->sendSms($numberPhone, $message, false, $oauth['access_token'] ?? '');

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
