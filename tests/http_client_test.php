<?php
declare(strict_types=1);
require __DIR__ . '/../src/NvoipClient.php';
use Nvoip\NvoipClient;

function expectHttp(bool $condition, string $message): void { if (!$condition) throw new RuntimeException($message); }
$port = random_int(20000, 40000);
$capture = tempnam(sys_get_temp_dir(), 'nvoip-capture-');
$process = proc_open(['php', '-S', "localhost:$port", __DIR__ . '/router.php'], [0 => ['pipe', 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes, null, ['NVOIP_TEST_CAPTURE' => $capture] + $_ENV);
for ($attempt = 0; $attempt < 20; $attempt++) { if (@fsockopen('localhost', $port)) break; usleep(100000); }
try {
    $base = "http://localhost:$port";
    $client = new NvoipClient($base . '/v3', 'client id', 's/secret', $base . '/token');
    $client->createClientCredentialsToken();
    $client->refreshAccessToken('a b');
    $client->getBalance('bearer-value');
    $client->checkOtp('123', 'key', 'bearer-value');
    try { (new NvoipClient($base . '/v3', 'id', 'secret', $base . '/error'))->createClientCredentialsToken(); throw new RuntimeException('Expected HTTP error.'); } catch (RuntimeException $error) { expectHttp(str_contains($error->getMessage(), '401'), 'Error response must be surfaced.'); }
    $records = array_map('json_decode', array_filter(explode("\n", file_get_contents($capture))));
    expectHttp($records[0]->path === '/token' && $records[0]->body === 'grant_type=client_credentials', 'Client credentials form mismatch.');
    expectHttp($records[0]->authorization === 'Basic Y2xpZW50JTIwaWQ6cyUyRnNlY3JldA==', 'Basic special-character encoding mismatch.');
    expectHttp($records[1]->body === 'grant_type=refresh_token&refresh_token=a+b', 'Refresh form mismatch.');
    expectHttp($records[2]->authorization === 'Bearer bearer-value' && $records[3]->authorization === 'Bearer bearer-value', 'Bearer header missing.');
    echo "PHP HTTP transport contract checks passed\n";
} finally { proc_terminate($process); proc_close($process); unlink($capture); }
