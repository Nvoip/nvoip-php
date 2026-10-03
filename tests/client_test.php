<?php
declare(strict_types=1);
require __DIR__ . '/../src/NvoipClient.php';

use Nvoip\NvoipClient;

function expect(bool $condition, string $message): void { if (!$condition) { throw new RuntimeException($message); } }

expect(NvoipClient::encodeBasicAuth('client id', 's/ecret') === base64_encode('client%20id:s%2Fecret'), 'Basic encoding must form-encode both credential fields.');
try {
    (new NvoipClient())->createClientCredentialsToken();
    throw new RuntimeException('Missing OAuth credentials must fail before an HTTP request.');
} catch (RuntimeException $error) {
    expect(str_contains($error->getMessage(), 'Missing OAuth'), 'Unexpected OAuth configuration error.');
}
echo "PHP OAuth offline contract checks passed\n";
