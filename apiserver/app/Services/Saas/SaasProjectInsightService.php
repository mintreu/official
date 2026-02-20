<?php

namespace App\Services\Saas;

use App\Models\Saas\LicensedSite;
use App\Models\Saas\SaasProject;
use App\Models\Saas\SaasSyncLog;
use App\Models\Saas\SiteStatEvent;
use Illuminate\Database\Eloquent\Builder;

class SaasProjectInsightService
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public function listForUser(int $userId, int $minutes = 1440): array
    {
        $projects = LicensedSite::query()
            ->where('user_id', $userId)
            ->pluck('source_project')
            ->filter()
            ->unique()
            ->values();

        return $projects->map(fn (string $project): array => $this->forUserProject($userId, $project, $minutes))->all();
    }

    /**
     * @return array<string, mixed>
     */
    public function forUserProject(int $userId, string $project, int $minutes = 1440): array
    {
        $from = now()->subMinutes(max(1, $minutes));
        $sites = LicensedSite::query()
            ->where('user_id', $userId)
            ->where('source_project', $project)
            ->get();

        $eventQuery = SiteStatEvent::query()
            ->where('source_project', $project)
            ->where('received_at', '>=', $from)
            ->where(function (Builder $q) use ($sites): void {
                $siteUuids = $sites->pluck('site_uuid')->filter()->values();
                $siteSlugs = $sites->pluck('site_slug')->filter()->values();
                if ($siteUuids->isNotEmpty()) {
                    $q->whereIn('site_uuid', $siteUuids);
                }
                if ($siteSlugs->isNotEmpty()) {
                    $q->orWhereIn('site_slug', $siteSlugs);
                }
            });

        $projectMeta = SaasProject::query()->where('slug', $project)->first();
        $latestSync = SaasSyncLog::query()
            ->where('project_slug', $project)
            ->latest('executed_at')
            ->first();

        $events = $eventQuery->get(['metrics']);
        $totals = $events->reduce(function (array $carry, SiteStatEvent $event): array {
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

        return [
            'project' => $project,
            'sites' => $sites->count(),
            'active_sites' => $sites->where('status', 'active')->count(),
            'vendors' => $sites->pluck('vendor_id')->filter()->unique()->count(),
            'events' => (int) $eventQuery->count(),
            'totals' => $totals,
            'last_event_at' => $eventQuery->max('received_at'),
            'last_heartbeat_at' => $projectMeta?->last_heartbeat_at?->toISOString(),
            'last_machine_info' => (array) ($projectMeta?->last_machine_info ?? []),
            'latest_sync' => $latestSync ? [
                'status' => $latestSync->status,
                'action' => $latestSync->action,
                'message' => $latestSync->message,
                'executed_at' => $latestSync->executed_at?->toISOString(),
            ] : null,
        ];
    }
}
