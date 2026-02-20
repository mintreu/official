# Progress Snapshot - 2026-02-20

## Completed

- Official SaaS backend core established for multi-child communication.
- Child provisioning pipeline persists licensed sites and supports additional site creation.
- Per-project signed internal API verification implemented.
- Heartbeat + site telemetry ingestion implemented with machine/runtime support.
- SaaS project/site/vendor/user insights APIs implemented for Nuxt dashboard.
- Bridge outbound client with sync logging implemented.
- Wallet + transaction system foundation implemented and tested.
- Product price storage standardized to integer minor units in migration baseline.

## Current API Surfaces Ready

- Internal signed endpoints:
  - `POST /api/internal/saas/project/heartbeat`
  - `POST /api/internal/saas/site-stats`
  - `POST /api/internal/saas/license/check`
  - `GET /api/internal/saas/insights/site/{siteUuid}`
  - `GET /api/internal/saas/insights/vendor/{vendorId}`
  - `GET /api/internal/saas/insights/overview`

- Authenticated user endpoints:
  - `GET /api/saas/sites`
  - `POST /api/saas/licenses/{license}/sites`
  - `GET /api/saas/sites/{siteUuid}`
  - `GET /api/saas/insights/overview`
  - `GET /api/saas/insights/projects/{project}`
  - `GET /api/saas/insights/vendors/{vendorId}`
  - `GET /api/saas/projects`
  - `GET /api/saas/projects/{project}`
  - `POST /api/saas/projects/{project}/ping`

## Tests Verified

- `MoneyServiceTest`
- `WalletTransactionSystemTest`
- `SaasSiteDashboardTest`
- `SaasProjectHeartbeatTest`
- `SaasProjectInsightsTest`

## Next Immediate Tasks

1. Wire Nuxt dashboard pages to new SaaS endpoints using `useSacntumFetch`.
2. Add child-side scheduler/job in shopcore/helpdesk for heartbeat + site-stats push.
3. Add sales pipeline CRM endpoints/panel in official (lead -> deal -> close).
4. Add production-only seed refinement for sale-ready products and plans.

## Resume Point (Saved for next session)

- Primary execution list now maintained in `.codex/todo.md` (P0/P1/P2).
- P0 blocker to start with: fix slug collision in `Tests\Feature\Api\SaasProjectInsightsTest` and make full `composer test` green.
- After test green:
  1. Finish no-ID exposure audit (UUID/slug only on public APIs/routes).
  2. Finalize purchase baseline (guest -> register -> checkout -> license/api key/site provisioning via service classes).
  3. Validate production-only seeding consistency for products/projects/case-studies/dashboard.
