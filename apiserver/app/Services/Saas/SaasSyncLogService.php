<?php

namespace App\Services\Saas;

use App\Models\Saas\SaasSyncLog;

class SaasSyncLogService
{
    /**
     * @param  array<string, mixed>|null  $requestPayload
     * @param  array<string, mixed>|null  $responsePayload
     */
    public function write(
        string $project,
        string $direction,
        string $action,
        string $status,
        ?int $httpStatus = null,
        ?string $targetUrl = null,
        ?string $message = null,
        ?array $requestPayload = null,
        ?array $responsePayload = null
    ): SaasSyncLog {
        return SaasSyncLog::query()->create([
            'project_slug' => $project,
            'direction' => $direction,
            'action' => $action,
            'status' => $status,
            'http_status' => $httpStatus,
            'target_url' => $targetUrl,
            'message' => $message,
            'request_payload' => $requestPayload,
            'response_payload' => $responsePayload,
            'executed_at' => now(),
        ]);
    }
}
