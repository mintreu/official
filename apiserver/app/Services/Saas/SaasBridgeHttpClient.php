<?php

namespace App\Services\Saas;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class SaasBridgeHttpClient
{
    public function __construct(
        private readonly SaasProjectRegistryService $registry,
        private readonly SaasSyncLogService $syncLog
    ) {}

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function post(string $project, string $path, array $payload, string $action = 'bridge.post'): array
    {
        $config = $this->registry->resolve($project);
        $baseUrl = (string) ($config['base_url'] ?? '');
        $key = (string) ($config['internal_key'] ?? '');
        $secret = (string) ($config['internal_secret'] ?? '');

        if ($baseUrl === '' || $key === '' || $secret === '') {
            $this->syncLog->write($project, 'outbound', $action, 'failed', null, null, 'Missing project bridge configuration.', $payload, null);

            return [
                'ok' => false,
                'status' => null,
                'message' => 'Missing project bridge configuration.',
                'data' => null,
            ];
        }

        $url = rtrim($baseUrl, '/').'/'.ltrim($path, '/');
        $timestamp = (string) now()->timestamp;
        $body = (string) json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $requestPath = ltrim((string) parse_url($url, PHP_URL_PATH), '/');
        $signatureBase = "{$timestamp}.POST.{$requestPath}.{$body}";
        $signature = hash_hmac('sha256', $signatureBase, $secret);

        try {
            $response = Http::timeout((int) ($config['timeout_seconds'] ?? 15))
                ->withHeaders([
                    'X-Mintreu-Project' => $project,
                    'X-Mintreu-Key' => $key,
                    'X-Mintreu-Timestamp' => $timestamp,
                    'X-Mintreu-Signature' => $signature,
                ])
                ->post($url, $payload);

            return $this->normalizeResponse($project, 'outbound', $action, $url, $payload, $response);
        } catch (\Throwable $e) {
            $this->syncLog->write(
                $project,
                'outbound',
                $action,
                'failed',
                null,
                $url,
                $e->getMessage(),
                $payload,
                null
            );

            return [
                'ok' => false,
                'status' => null,
                'message' => $e->getMessage(),
                'data' => null,
            ];
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function getPublic(string $project, string $path, string $action = 'bridge.get'): array
    {
        $config = $this->registry->resolve($project);
        $baseUrl = (string) ($config['base_url'] ?? '');
        if ($baseUrl === '') {
            $this->syncLog->write($project, 'outbound', $action, 'failed', null, null, 'Missing project base URL.', null, null);

            return [
                'ok' => false,
                'status' => null,
                'message' => 'Missing project base URL.',
                'data' => null,
            ];
        }

        $url = rtrim($baseUrl, '/').'/'.ltrim($path, '/');

        try {
            $response = Http::timeout((int) ($config['timeout_seconds'] ?? 15))->get($url);

            return $this->normalizeResponse($project, 'outbound', $action, $url, [], $response);
        } catch (\Throwable $e) {
            $this->syncLog->write($project, 'outbound', $action, 'failed', null, $url, $e->getMessage(), null, null);

            return [
                'ok' => false,
                'status' => null,
                'message' => $e->getMessage(),
                'data' => null,
            ];
        }
    }

    /**
     * @param  array<string, mixed>  $requestPayload
     * @return array<string, mixed>
     */
    private function normalizeResponse(
        string $project,
        string $direction,
        string $action,
        string $url,
        array $requestPayload,
        Response $response
    ): array {
        $payload = (array) $response->json();
        $ok = $response->successful();
        $message = $ok ? 'Bridge request successful.' : ((string) ($payload['message'] ?? 'Bridge request failed.'));

        $this->syncLog->write(
            $project,
            $direction,
            $action,
            $ok ? 'success' : 'failed',
            $response->status(),
            $url,
            $message,
            $requestPayload,
            $payload
        );

        return [
            'ok' => $ok,
            'status' => $response->status(),
            'message' => $message,
            'data' => $payload,
        ];
    }
}
