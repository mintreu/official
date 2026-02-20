# Official project notes

Purpose:
- Digital product marketplace + SaaS/API subscription sales
- No physical stock/shipping
- Referral program (not MLM)

Planned reuse from commerinity:
- Payment/transaction architecture (Cashfree + webhooks + verify)
- Refund flow
- Voucher/sale validation (adapt to digital)
- Helpdesk + FAQ modules
- Notifications (user/admin)

Key requirements:
- Auth required for paid content
- License issuance + API key generation after payment
- Subscription renewals, cancellations, expiry tracking
- Referral sale tracking + payouts

Rules:
- Do not over-engineer
- Ask user when unclear
- Mark fixes only after user confirmation

Reminder: Ask user which other projects exist/active and request their paths when starting Official work.

---

## Official project understanding (snapshot: 2026-01-30)

Stack:
- Backend: Laravel 12.35.0 (PHP 8.4.15), Sanctum 4.2, Livewire 3.6, Filament 4.0
- Frontend: Nuxt (Nuxt 4 starter), Tailwind 4.1
- DB: MySQL (per app info + docs)

Repo layout:
- apiserver/ -> Laravel API (Sanctum auth, Filament admin, Livewire checkout per docs)
- client/    -> Nuxt frontend
- docs/      -> system architecture + deployment + schema notes
- plans/     -> long-term roadmap + marketplace plans

Confirmed schema scope (docs/database-schema.md):
- products, product_sources, licenses, download_logs
- api_keys, api_endpoints, api_usage_logs, plans
- external_api_credentials, advertisements

Known blocker (code):
- apiserver/app/Http/Controllers/Api/ProductController.php contains a literal "\n" token in PHP,
  causing a parse error ("unexpected variable $paginator") and breaking route listing.

Notes:
- apiserver/README.md and client/README.md are default templates (no project-specific guidance).
- .codex/decision-log.md referenced in CODEX.md was not found in repo at time of scan.

## Sanctum `/user` override strategy (per new directive)

- Override the default `/api/user` route so `GET /api/user` returns a rich `UserIndexResource` with verification status, avatar/referral data, and membership hints.
- Provide a heavier `/api/user/profile` endpoint only when screens require addresses, wallet, KYC, or subscription objects.
- Frontend must call `useSanctum()` (the auto-invoked `/api/user` call) for basic state, and explicit `useSanctumFetch('/api/user/profile')` only when needed.
- All other backend calls should use `useSanctumFetch` (directly or via `useApi()` helpers) so CSRF/auth are handled consistently; avoid `useAsyncData` / `useFetch`.

## Inter-project governance memory (Official <-> Shopcore)

Context date: 2026-02-19

- Leadership model:
  - Founder + `mintreu-ai-cofounder` are leadership.
  - Shopcore `codex-main` acts as execution staff and must report with evidence.

- Scope split:
  - Official side: API contracts and governance/control only for now.
  - Shopcore side: active implementation for child API + client dashboard structure.

- Official client responsibility:
  - Source of truth for API subscription billing, usage analytics, limits, renewal/cancel state, and allocation/access governance.
  - Official client will display per API project space/site usage and control state via API pull.

- Shopcore client responsibility:
  - Vendor acts as admin for own shop/site operations.
  - Must implement clean route separation: guest, user dashboard, admin/vendor dashboard.
  - Admin pages/components grouped under `admin/` for easy separation/reuse.
  - Manage project-domain models (products, categories, project customers/users, orders, and related site models).
  - Must NOT include API billing/subscription management UI.
  - Can show only a simple animated/color usage bar.

- Interlock requirements:
  - Paid order/subscription must map to vendor account creation/ensure + site allocation by plan capacity.
  - Child enforces limits/access by Official rules.
  - Child reports stats to Official for billing/governance.
  - License-free run is not allowed in the target model.

- Delivery style:
  - Minimal safe changes only; no broad refactor.
  - If doubt/conflict exists, stop and ask leadership before implementation.

- Site identifier rule (hard):
  - In API calls/contracts, always use site UUID as canonical identifier.
  - Do not use site numeric id/url/slug as authoritative keys for billing, control, enforcement, or interlock endpoints.
  - Site url/slug may be metadata/display fields only.
  - Nuxt frontend product/display routing may use slug in URL, but site-scoped API fetches must pass `site_uuid`.

---

## 2026-02-20 - Current production-ready SaaS bridge state

Completed in `official/apiserver`:

- Money + pricing foundation:
  - MoneyPHP wrapper service ported and tested.
  - Product `price` moved to integer minor units (paise) at schema level for clean reset flow.
  - Plan display/currency formatting aligned to INR.

- Full wallet + transaction base:
  - Wallet and Transaction models extended to support shopcore-style fields.
  - Enums/casts added for payment method, transaction type, transaction status.
  - `HasTransaction` trait added and attached to License model for payable flow.
  - Wallet service implemented (`credit`, `debit`, `hold`, `release`).
  - Subscription billing now records real transaction rows and wallet-aware status.

- SaaS site lifecycle + vendor/site operations:
  - `licensed_sites` table/model added for mapping user license -> provisioned child site.
  - Subscription provisioning persists site records.
  - Extra site creation endpoint implemented with plan site limit enforcement.
  - User dashboard endpoints added for site list, site detail, project/vendor/overview insights.

- Official <-> child project communication core:
  - Project registry + heartbeat tracking tables added (`saas_projects`, `saas_sync_logs`).
  - Per-project signature verification implemented (`X-Mintreu-Project` aware auth).
  - Bridge HTTP client added for outbound signed calls with centralized sync logging.
  - Internal heartbeat endpoint added for child server machine/runtime status push.
  - Internal site telemetry ingestion extended to include requests/errors + machine/runtime payload.
  - Insights service extended for project cards, site cards, machine snapshot, user/project rollups.

- Docs:
  - Runtime contract added at `apiserver/docs/saas-bridge-runtime.md`.

Validated:

- Migrations executed successfully for new SaaS tables.
- Tests passing:
  - `MoneyServiceTest`
  - `WalletTransactionSystemTest`
  - `SaasSiteDashboardTest`
  - `SaasProjectHeartbeatTest`
  - `SaasProjectInsightsTest`

Operational note:

- Current provisioning and heartbeat are real-key/signature driven. Missing/invalid project credentials now fail explicitly instead of mock success behavior.

## 2026-02-20 - Latest checkpoint before pause

- User requested production hardening with premium dashboard UX, no dummy data, UUID-first exposure policy, and service-class-first license lifecycle.
- Saved prioritized execution plan in `.codex/todo.md`.
- Readiness verification result:
  - Frontend build passes (`client npm run build`).
  - Backend tests not fully green yet (`composer test` has 1 failure from duplicate slug collision in `SaasProjectInsightsTest`).
- Therefore, project is not yet marked 100% production-ready until P0 blockers are closed.
