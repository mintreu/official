<?php

namespace App\Services\Saas;

use App\Models\Saas\LicensedSite;
use App\Models\Saas\SiteStatEvent;
use Carbon\CarbonImmutable;

class SaasStatIngestionService
{
    /**
     * @param  array<string, mixed>  $payload
     */
    public function ingest(array $payload, string $sourceProject): SiteStatEvent
    {
        $metrics = (array) ($payload['metrics'] ?? []);
        $siteUuid = isset($payload['site_uuid']) ? (string) $payload['site_uuid'] : null;
        $siteSlug = isset($payload['site_slug']) ? (string) $payload['site_slug'] : null;

        $event = SiteStatEvent::query()->create([
            'source_project' => $sourceProject !== '' ? $sourceProject : 'unknown',
            'vendor_id' => isset($payload['vendor_id']) ? (int) $payload['vendor_id'] : null,
            'site_id' => isset($payload['site_id']) ? (int) $payload['site_id'] : null,
            'site_uuid' => $siteUuid,
            'site_slug' => $siteSlug,
            'window_start' => $this->parseTimestamp($payload['window_start'] ?? null),
            'window_end' => $this->parseTimestamp($payload['window_end'] ?? null),
            'metrics' => [
                'orders_count' => (int) ($metrics['orders_count'] ?? 0),
                'new_users_count' => (int) ($metrics['new_users_count'] ?? 0),
                'revenue_paise' => (int) ($metrics['revenue_paise'] ?? 0),
                'requests_count' => (int) ($metrics['requests_count'] ?? 0),
                'errors_count' => (int) ($metrics['errors_count'] ?? 0),
            ],
            'payload' => $payload,
            'received_at' => now(),
        ]);

        if (($siteUuid ?? '') !== '' || ($siteSlug ?? '') !== '') {
            LicensedSite::query()
                ->where('source_project', $sourceProject)
                ->when($siteUuid, fn ($q) => $q->where('site_uuid', $siteUuid))
                ->when(! $siteUuid && $siteSlug, fn ($q) => $q->where('site_slug', $siteSlug))
                ->update([
                    'last_seen_at' => now(),
                    'meta' => [
                        'last_machine' => (array) ($payload['machine'] ?? []),
                        'last_runtime' => (array) ($payload['runtime'] ?? []),
                    ],
                ]);
        }

        return $event;
    }

    private function parseTimestamp(mixed $value): ?CarbonImmutable
    {
        if (! is_string($value) || $value === '') {
            return null;
        }

        try {
            return CarbonImmutable::parse($value);
        } catch (\Throwable) {
            return null;
        }
    }
}
