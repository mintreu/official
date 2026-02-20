# TODO (Official) - Priority Ordered

## P0 - Launch Blockers (Must fix before production)

1. Fix failing backend test suite to green
- `Tests\Feature\Api\SaasProjectInsightsTest` fails due to duplicate `products.slug` (`shopcore-commerce-api`).
- Update factory/test setup so slug collision cannot happen in refresh DB runs.
- Re-run `composer test` and ensure full pass.

2. Complete ID hardening (no numeric ID exposure)
- Audit all API resources/routes and dashboard links for `id` exposure.
- Keep only `uuid` / `slug` in public payloads and route params.
- Add regression tests for key endpoints (`licenses`, `api-spaces`, `saas/projects`).

3. Purchase flow baseline (production-safe)
- Implement guest -> registration -> checkout -> subscription activation path end-to-end.
- Ensure license + API key + site/space provisioning are generated via service classes only.
- Validate renewal and re-license flow for existing customers.

4. Seeder integrity for production-only data
- Ensure no dummy/demo/fake copy remains in homepage/dashboard/case studies/products/projects.
- Confirm seeder output is relationally consistent (projects <-> products <-> case studies <-> licenses).
- Run clean seed on fresh DB and verify UI pages.

## P1 - High Priority (Immediately after P0)

5. Dashboard IA + UX finalization
- Make API Services -> Sites/Spaces -> License actions the primary flow.
- Add missing clickable CTAs and route guards across landing + dashboard.
- Finish premium widget states (loading/empty/error/success) for all major sections.

6. Site/Space lifecycle and insights completion
- Finalize create/edit/disable/restore lifecycle (soft delete + status sync).
- Show per-site usage, health, and billing caps with clear actions.
- Ensure child project heartbeat + usage ingestion maps correctly in official dashboard.

7. Docs/Knowledgebase integration
- Add API guideline docs section (PDF/doc links + KB/blog entries) per API service product.
- Link docs from product page + dashboard service detail page.

8. Reviews and ratings completion
- Finish ordered-product/service review flow (write/update/list moderation strategy if needed).
- Show trust signals in product detail and dashboard history.

## P2 - Scale Readiness (Post-launch but planned now)

9. Payment gateway finalization (4 gateways target)
- Stripe, Cashfree, Razorpay, Native/manual flow (cash/backoffice).
- Add webhook verification + transaction reconciliation + retry handling.

10. Observability and runtime safety
- Add structured logs, alerting hooks, and audit trail for license/site actions.
- Build health checks for official <-> child API communication and license validation.

11. Deployment checklist and rollback plan
- Local (`*.mintreu.test`) and production (`*.mintreu.com`) env parity checklist.
- Versioned migration/seed/deploy runbook and rollback steps.
