<?php
declare(strict_types=1);
$record = [
    'path' => $_SERVER['REQUEST_URI'],
    'method' => $_SERVER['REQUEST_METHOD'],
    'authorization' => $_SERVER['HTTP_AUTHORIZATION'] ?? '',
    'body' => file_get_contents('php://input'),
];
file_put_contents(getenv('NVOIP_TEST_CAPTURE'), json_encode($record) . "\n", FILE_APPEND);
if ($_SERVER['REQUEST_URI'] === '/error') { http_response_code(401); echo '{"error":"invalid"}'; return; }
header('Content-Type: application/json');
echo '{}';
