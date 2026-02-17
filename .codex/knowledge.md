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
