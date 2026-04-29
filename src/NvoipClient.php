<?php

declare(strict_types=1);

namespace Nvoip;

use RuntimeException;

final class NvoipClient
{
    private const BASIC_AUTH = 'TnZvaXBBcGlWMjpUblp2YVhCQmNHbFdNakl3TWpFPQ==';

    public function __construct(
        private readonly string $baseUrl = 'https://api.nvoip.com.br/v2'
    ) {
    }

    public function createAccessToken(string $numbersip, string $userToken): array
    {
        return $this->request(
            'POST',
            '/oauth/token',
            [
                'Content-Type: application/x-www-form-urlencoded',
                'Authorization: Basic ' . self::BASIC_AUTH,
            ],
            http_build_query(
                [
                    'username' => $numbersip,
                    'password' => $userToken,
                    'grant_type' => 'password',
                ]
            )
        );
    }

    public function refreshAccessToken(string $refreshToken): array
    {
        return $this->request(
            'POST',
            '/oauth/token',
            [
                'Content-Type: application/x-www-form-urlencoded',
                'Authorization: Basic ' . self::BASIC_AUTH,
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
        bool $flashSms = false,
        ?string $accessToken = null,
        ?string $napikey = null
    ): array {
        return $this->jsonRequest(
            'POST',
            '/sms',
            [
                'numberPhone' => $numberPhone,
                'message' => $message,
                'flashSms' => $flashSms,
            ],
            $accessToken,
            $napikey
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

    public function getCall(string $callId, ?string $accessToken = null, ?string $napikey = null): array
    {
        $path = '/calls?callId=' . rawurlencode($callId);
        if ($napikey !== null && $napikey !== '') {
            $path .= '&napikey=' . rawurlencode($napikey);
        }

        return $this->request(
            'GET',
            $path,
            $accessToken !== null && $accessToken !== ''
                ? ['Authorization: Bearer ' . $accessToken]
                : []
        );
    }

    public function sendOtp(
        array $payload,
        ?string $accessToken = null,
        ?string $napikey = null
    ): array {
        return $this->jsonRequest('POST', '/otp', $payload, $accessToken, $napikey);
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

    private function jsonRequest(
        string $method,
        string $path,
        array $payload,
        ?string $accessToken = null,
        ?string $napikey = null
    ): array {
        if ($napikey !== null && $napikey !== '') {
            $separator = str_contains($path, '?') ? '&' : '?';
            $path .= $separator . 'napikey=' . rawurlencode($napikey);
        }

        $headers = ['Content-Type: application/json'];
        if ($accessToken !== null && $accessToken !== '') {
            $headers[] = 'Authorization: Bearer ' . $accessToken;
        }

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
                CURLOPT_URL => rtrim($this->baseUrl, '/') . $path,
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
