<?php

declare(strict_types=1);

namespace Nvoip;

use RuntimeException;

final class NvoipClient
{
    public function __construct(
        private readonly string $baseUrl = 'https://api.nvoip.com.br/v3',
        private readonly ?string $oauthClientId = null,
        private readonly ?string $oauthClientSecret = null,
        private readonly string $tokenUrl = 'https://api.nvoip.com.br/auth/oauth2/token'
    ) {
    }

    public static function encodeBasicAuth(string $clientId, string $clientSecret): string
    {
        return base64_encode(rawurlencode($clientId) . ':' . rawurlencode($clientSecret));
    }

    public function createClientCredentialsToken(): array
    {
        return $this->request(
            'POST',
            $this->tokenUrl,
            [
                'Content-Type: application/x-www-form-urlencoded',
                'Authorization: Basic ' . $this->resolveBasicAuth(),
            ],
            http_build_query(
                [
                    'grant_type' => 'client_credentials',
                ]
            )
        );
    }

    public function refreshAccessToken(string $refreshToken): array
    {
        return $this->request(
            'POST',
            $this->tokenUrl,
            [
                'Content-Type: application/x-www-form-urlencoded',
                'Authorization: Basic ' . $this->resolveBasicAuth(),
            ],
            http_build_query(
                [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $refreshToken,
                ]
            )
        );
    }

    public function getBalance(string $accessToken): array
    {
        return $this->request(
            'GET',
            '/balance',
            [
                'Authorization: Bearer ' . $accessToken,
            ]
        );
    }

    public function sendSms(
        string $numberPhone,
        string $message,
        string $accessToken,
        bool $flashSms = false
    ): array {
        return $this->jsonRequest(
            'POST',
            '/sms',
            [
                'numberPhone' => $numberPhone,
                'message' => $message,
                'flashSms' => $flashSms,
            ],
            $accessToken
        );
    }

    public function createCall(string $caller, string $called, string $accessToken): array
    {
        return $this->jsonRequest(
            'POST',
            '/calls/',
            [
                'caller' => $caller,
                'called' => $called,
            ],
            $accessToken
        );
    }

    public function getCall(string $callId, string $accessToken): array
    {
        $path = '/calls?callId=' . rawurlencode($callId);

        return $this->request(
            'GET',
            $path,
            ['Authorization: Bearer ' . $accessToken]
        );
    }

    public function sendOtp(
        array $payload,
        string $accessToken
    ): array {
        return $this->jsonRequest('POST', '/otp', $payload, $accessToken);
    }

    public function checkOtp(string $code, string $key, string $accessToken): array
    {
        return $this->request(
            'GET',
            '/check/otp?code=' . rawurlencode($code) . '&key=' . rawurlencode($key),
            ['Authorization: Bearer ' . $accessToken]
        );
    }

    public function listWhatsAppTemplates(string $accessToken): array
    {
        return $this->request(
            'GET',
            '/wa/listTemplates',
            [
                'Authorization: Bearer ' . $accessToken,
            ]
        );
    }

    public function sendWhatsAppTemplate(array $payload, string $accessToken): array
    {
        return $this->jsonRequest('POST', '/wa/sendTemplates', $payload, $accessToken);
    }

    private function resolveBasicAuth(): string
    {
        if (
            $this->oauthClientId !== null && $this->oauthClientId !== '' &&
            $this->oauthClientSecret !== null && $this->oauthClientSecret !== ''
        ) {
            return self::encodeBasicAuth($this->oauthClientId, $this->oauthClientSecret);
        }

        throw new RuntimeException(
            'Missing OAuth client credentials. Configure oauthClientId + oauthClientSecret.'
        );
    }

    private function jsonRequest(
        string $method,
        string $path,
        array $payload,
        string $accessToken
    ): array {
        $headers = ['Content-Type: application/json'];
        $headers[] = 'Authorization: Bearer ' . $accessToken;

        return $this->request($method, $path, $headers, json_encode($payload, JSON_THROW_ON_ERROR));
    }

    private function request(
        string $method,
        string $path,
        array $headers,
        ?string $body = null
    ): array {
        $curl = curl_init();
        if ($curl === false) {
            throw new RuntimeException('Unable to initialize cURL.');
        }

        curl_setopt_array(
            $curl,
            [
                CURLOPT_URL => str_starts_with($path, 'http') ? $path : rtrim($this->baseUrl, '/') . $path,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => $method,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_TIMEOUT => 30,
            ]
        );

        if ($body !== null) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        }

        $rawResponse = curl_exec($curl);
        $statusCode = (int) curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        $curlError = curl_error($curl);
        curl_close($curl);

        if ($rawResponse === false) {
            throw new RuntimeException('Nvoip request failed: ' . $curlError);
        }

        $decoded = json_decode($rawResponse, true);
        $payload = is_array($decoded) ? $decoded : ['raw' => $rawResponse];

        if ($statusCode >= 400) {
            throw new RuntimeException(
                sprintf('Nvoip request failed with status %d: %s', $statusCode, $rawResponse)
            );
        }

        return $payload;
    }
}
