<?php

namespace App\Services\Saas;

use App\Models\Saas\LicensedSite;
use App\Models\Saas\SiteStatEvent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class SaasInsightsService
{
    /**
     * @return array<string, mixed>
     */
    public function site(string $siteUuid, int $minutes = 60): array
    {
        $from = Carbon::now()->subMinutes(max(1, $minutes));
        $query = SiteStatEvent::query()
            ->where('site_uuid', $siteUuid)
            ->where('received_at', '>=', $from);

        return [
            'window_minutes' => max(1, $minutes),
            'site_uuid' => $siteUuid,
            'events' => (int) $query->count(),
            'totals' => $this->sumMetrics(clone $query),
            'last_received_at' => $query->max('received_at'),
            'latest_machine' => $this->latestMachineInfo(clone $query),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function vendor(int $vendorId, int $minutes = 60): array
    {
        $from = Carbon::now()->subMinutes(max(1, $minutes));
        $query = SiteStatEvent::query()
            ->where('vendor_id', $vendorId)
            ->where('received_at', '>=', $from);

        return [
            'window_minutes' => max(1, $minutes),
            'vendor_id' => $vendorId,
            'events' => (int) $query->count(),
            'sites' => (int) (clone $query)->distinct('site_uuid')->count('site_uuid'),
            'totals' => $this->sumMetrics(clone $query),
            'last_received_at' => (clone $query)->max('received_at'),
            'latest_machine_by_site' => $this->latestMachineBySite(clone $query),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function overview(int $minutes = 60): array
    {
        $from = Carbon::now()->subMinutes(max(1, $minutes));
        $query = SiteStatEvent::query()->where('received_at', '>=', $from);

        return [
            'window_minutes' => max(1, $minutes),
            'events' => (int) $query->count(),
            'vendors' => (int) (clone $query)->distinct('vendor_id')->count('vendor_id'),
            'sites' => (int) (clone $query)->distinct('site_uuid')->count('site_uuid'),
            'totals' => $this->sumMetrics(clone $query),
            'last_received_at' => (clone $query)->max('received_at'),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function userOverview(int $userId, int $minutes = 1440): array
    {
        $from = Carbon::now()->subMinutes(max(1, $minutes));
        $sites = LicensedSite::query()->where('user_id', $userId)->get();
        $siteUuids = $sites->pluck('site_uuid')->filter()->values();
        $siteSlugs = $sites->pluck('site_slug')->filter()->values();

        $query = SiteStatEvent::query()
            ->where('received_at', '>=', $from)
            ->where(function (Builder $q) use ($siteUuids, $siteSlugs): void {
                if ($siteUuids->isNotEmpty()) {
                    $q->whereIn('site_uuid', $siteUuids);
                }
                if ($siteSlugs->isNotEmpty()) {
                    $q->orWhereIn('site_slug', $siteSlugs);
                }
            });

        return [
            'window_minutes' => max(1, $minutes),
            'totals' => $this->sumMetrics(clone $query),
            'events' => (int) $query->count(),
            'licensed_sites' => $sites->count(),
            'active_projects' => $sites->pluck('source_project')->filter()->unique()->values(),
            'last_received_at' => (clone $query)->max('received_at'),
            'project_breakdown' => $sites->groupBy('source_project')->map(function ($group, $project) use ($from): array {
                $projectQuery = SiteStatEvent::query()
                    ->where('source_project', (string) $project)
                    ->where('received_at', '>=', $from)
                    ->where(function (Builder $q) use ($group): void {
                        $siteUuids = $group->pluck('site_uuid')->filter()->values();
                        $siteSlugs = $group->pluck('site_slug')->filter()->values();
                        if ($siteUuids->isNotEmpty()) {
                            $q->whereIn('site_uuid', $siteUuids);
                        }
                        if ($siteSlugs->isNotEmpty()) {
                            $q->orWhereIn('site_slug', $siteSlugs);
                        }
                    });

                return [
                    'project' => $project,
                    'sites' => $group->count(),
                    'events' => (int) $projectQuery->count(),
                    'totals' => $this->sumMetrics(clone $projectQuery),
                ];
            })->values(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function projectForUser(int $userId, string $project, int $minutes = 1440): array
    {
        $from = Carbon::now()->subMinutes(max(1, $minutes));
        $sites = LicensedSite::query()
            ->where('user_id', $userId)
            ->where('source_project', $project)
            ->get();

        $siteUuids = $sites->pluck('site_uuid')->filter()->values();
        $siteSlugs = $sites->pluck('site_slug')->filter()->values();

        $query = SiteStatEvent::query()
            ->where('source_project', $project)
            ->where('received_at', '>=', $from)
            ->where(function (Builder $q) use ($siteUuids, $siteSlugs): void {
                if ($siteUuids->isNotEmpty()) {
                    $q->whereIn('site_uuid', $siteUuids);
                }
                if ($siteSlugs->isNotEmpty()) {
                    $q->orWhereIn('site_slug', $siteSlugs);
                }
            });

        return [
            'project' => $project,
            'window_minutes' => max(1, $minutes),
            'sites' => $sites->count(),
            'events' => (int) $query->count(),
            'totals' => $this->sumMetrics(clone $query),
            'last_received_at' => (clone $query)->max('received_at'),
            'machine_by_site' => $this->latestMachineBySite(clone $query),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function siteCard(string $siteIdentifier, string $project, int $vendorId = 0): array
    {
        $from = Carbon::now()->subMinutes(60 * 24);
        $query = SiteStatEvent::query()
            ->where('source_project', $project)
            ->where('received_at', '>=', $from)
            ->where(function (Builder $q) use ($siteIdentifier): void {
                $q->where('site_uuid', $siteIdentifier)
                    ->orWhere('site_slug', $siteIdentifier);
            });

        if ($vendorId > 0) {
            $query->where('vendor_id', $vendorId);
        }

        return [
            'window_minutes' => 60 * 24,
            'events' => (int) $query->count(),
            'totals' => $this->sumMetrics(clone $query),
            'last_received_at' => (clone $query)->max('received_at'),
            'latest_machine' => $this->latestMachineInfo(clone $query),
        ];
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder<SiteStatEvent>  $query
     * @return array<string, int>
     */
    private function sumMetrics($query): array
    {
        $events = $query->get(['metrics']);

        return $events->reduce(function (array $carry, SiteStatEvent $event): array {
            $metrics = (array) $event->metrics;
            $carry['orders_count'] += (int) ($metrics['orders_count'] ?? 0);
            $carry['new_users_count'] += (int) ($metrics['new_users_count'] ?? 0);
            $carry['revenue_paise'] += (int) ($metrics['revenue_paise'] ?? 0);
            $carry['requests_count'] += (int) ($metrics['requests_count'] ?? 0);
            $carry['errors_count'] += (int) ($metrics['errors_count'] ?? 0);

            return $carry;
        }, [
            'orders_count' => 0,
            'new_users_count' => 0,
            'revenue_paise' => 0,
            'requests_count' => 0,
            'errors_count' => 0,
        ]);
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder<SiteStatEvent>  $query
     * @return array<string, mixed>|null
     */
    private function latestMachineInfo($query): ?array
    {
        $event = $query->latest('received_at')->first(['payload', 'metrics', 'received_at']);
        if (! $event) {
            return null;
        }

        $payload = (array) ($event->payload ?? []);
        $machine = (array) ($payload['machine'] ?? []);
        $runtime = (array) ($payload['runtime'] ?? []);

        return [
            'received_at' => $event->received_at?->toISOString(),
            'host' => $machine['host'] ?? null,
            'os' => $machine['os'] ?? null,
            'kernel' => $machine['kernel'] ?? null,
            'cpu' => $machine['cpu'] ?? null,
            'memory_total_mb' => isset($machine['memory_total_mb']) ? (int) $machine['memory_total_mb'] : null,
            'memory_used_mb' => isset($machine['memory_used_mb']) ? (int) $machine['memory_used_mb'] : null,
            'node' => $runtime['node'] ?? null,
            'nuxt' => $runtime['nuxt'] ?? null,
            'app_version' => $runtime['app_version'] ?? null,
        ];
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder<SiteStatEvent>  $query
     * @return array<int, array<string, mixed>>
     */
    private function latestMachineBySite($query): array
    {
        $events = $query->latest('received_at')->get(['site_uuid', 'site_slug', 'payload', 'received_at']);
        $grouped = $events->groupBy(function (SiteStatEvent $event): string {
            return (string) ($event->site_uuid ?: $event->site_slug ?: 'unknown');
        });

        return $grouped->map(function ($siteEvents, $siteKey): array {
            /** @var SiteStatEvent $event */
            $event = $siteEvents->first();
            $payload = (array) ($event->payload ?? []);
            $machine = (array) ($payload['machine'] ?? []);
            $runtime = (array) ($payload['runtime'] ?? []);

            return [
                'site' => $siteKey,
                'received_at' => $event->received_at?->toISOString(),
                'host' => $machine['host'] ?? null,
                'os' => $machine['os'] ?? null,
                'cpu' => $machine['cpu'] ?? null,
                'memory_used_mb' => isset($machine['memory_used_mb']) ? (int) $machine['memory_used_mb'] : null,
                'node' => $runtime['node'] ?? null,
                'nuxt' => $runtime['nuxt'] ?? null,
            ];
        })->values()->all();
    }
}
