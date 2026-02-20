# Shopcore -> Official SaaS Bridge

Date: 2026-02-19  
Status: Implemented in `official` + `laravel-saas-core`

## Goal
Collect real-time insights in Official at:
1. site level
2. vendor level
3. global aggregate level

## Official Endpoints
1. `POST /api/internal/saas/site-stats`
2. `GET /api/internal/saas/insights/site/{siteUuid}?minutes=60`
3. `GET /api/internal/saas/insights/vendor/{vendorId}?minutes=60`
4. `GET /api/internal/saas/insights/overview?minutes=60`
5. `GET /api/internal/saas/plan/resolve?project=shopcore&product_slug=...`

## Security Contract
Headers required:
1. `X-Mintreu-Key`
2. `X-Mintreu-Timestamp`
3. `X-Mintreu-Signature`
4. `X-Mintreu-Project` (for stats push source tagging)

Signature accepted:
1. `hash_hmac('sha256', "{timestamp}.{rawBody}", secret)` (stats push)
2. `hash_hmac('sha256', "{timestamp}.{METHOD}.{path}.{rawBody}", secret)` (internal GET/POST)

## Shopcore Package Env
1. `MINTREU_PROJECT_KEY=shopcore`
2. `MINTREU_OFFICIAL_BASE_URL=https://official-domain`
3. `MINTREU_OFFICIAL_SITE_STATS_PATH=/api/internal/saas/site-stats`
4. `MINTREU_OFFICIAL_API_KEY=...`
5. `MINTREU_OFFICIAL_WEBHOOK_SECRET=...`

## Scheduler
1. `saas:stats-snapshot --minutes=5` every 5 minutes
2. `saas:stats-push --limit=200` every minute
3. `saas:license-check` every 15 minutes

## Data Stored in Official
Table: `saas_site_stat_events`

Core identity fields:
1. `source_project`
2. `vendor_id`
3. `site_id`
4. `site_uuid`
5. `site_slug`
6. `window_start`
7. `window_end`
8. `metrics`

This gives per-site, per-vendor, and portfolio-wide live insight queries.
