# Decision Log (Official)

## 2026-02-19 - Shopcore execution governance and scope split

Decision:
- Founder + `mintreu-ai-cofounder` are leadership.
- Shopcore `codex-main` is execution staff.
- Official client has no direct implementation work in this phase from Shopcore side.
- Shopcore must implement child project admin/vendor dashboard and domain operations.
- Official must receive APIs/contracts for usage/limits/access/subscription state.

Rationale:
- Fastest path to production-ready revenue flow with clear ownership and minimal cross-repo confusion.
- Prevents duplicate UI responsibilities and avoids wrong-scope work.

Constraints:
- Shopcore client:
  - No billing management screens.
  - Only usage awareness bar allowed.
  - Guest/user/admin routes must be separated.
  - Admin pages/components grouped under `admin/`.
- Official client:
  - Billing, subscription, limits, access governance source of truth.
  - Per project-space/site analytics and control.

Execution policy:
- Minimal safe patches only.
- Evidence-based updates (paths + tests/logs).
- Any ambiguity must be escalated before coding.

## 2026-02-19 - Site UUID only in APIs

Decision:
- Use site UUID as the canonical site identifier in APIs/contracts.
- Do not use site id, url, or slug as authoritative lookup keys.

Rationale:
- Prevents ambiguity/collision and keeps inter-project billing/enforcement consistent.

Constraint:
- url/slug can exist as metadata/display only.

## 2026-02-19 - Slug allowed in UI route, UUID mandatory in API

Decision:
- Nuxt route URLs can use slug for product/display navigation.
- Site-scoped API calls must include and use `site_uuid` for authoritative data fetch/enforcement.

Rationale:
- Keeps user-friendly URLs while preserving strict backend identity integrity.

## 2026-02-20 - Migration strategy switched to clean-reset baseline

Decision:
- Since deployment is not done yet, keep migration history clean by editing base product migration and removing conversion-only migration.

Applied:
- `create_products_table` now defines `price` as integer minor unit.
- conversion migration for decimal->int was removed.
- verified with `migrate:fresh --force` + tests.

Rationale:
- Pre-deploy stage allows clean schema baseline without legacy transform debt.

## 2026-02-20 - No dummy bridge behavior policy

Decision:
- Bridge/payment/subscription paths should not return fake success in production workflow.
- Missing credential/config should return explicit failure.

Rationale:
- Prevents false-positive provisioning and revenue leakage.

## 2026-02-20 - Official becomes central SaaS communication core

Decision:
- Official owns child communication runtime:
  - per-project auth/signature verification
  - heartbeat
  - sync logs
  - licensed site registry
  - user-facing project/site/vendor insights APIs

Rationale:
- Required for multi-child API governance, sales operations, and reliable dashboard data.

Scope outcome:
- Added `licensed_sites`, `saas_projects`, `saas_sync_logs`.
- Added internal heartbeat endpoint and outbound bridge client.
- Added SaaS site/project insight APIs for authenticated users.

## 2026-02-20 - Production readiness gate policy

Decision:
- Do not declare "100% bug-free / enterprise-ready" unless both conditions are true:
  1. backend test suite is fully green
  2. frontend production build passes

Current checkpoint:
- frontend build passes
- backend suite has 1 failing test (`SaasProjectInsightsTest` slug collision)

Rationale:
- Prevents false readiness claims before launch week.
