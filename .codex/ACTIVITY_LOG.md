2026-02-14: Recorded cleanup instructions + todo refresh for the vendor-focused ecom project.
2026-02-20: Ported Money service + tests, converted price storage model to minor-unit integer baseline, and validated with fresh migrations/tests.
2026-02-20: Implemented wallet/transaction foundation in official (casts, trait, service layer, subscription billing integration).
2026-02-20: Verified shopcore vendor CRUD behavior (create/read/update/soft delete/force delete) via MCP tinker checks.
2026-02-20: Added licensed site registry + APIs for site cards, create site from license, and user SaaS insights.
2026-02-20: Added project registry + sync logs + heartbeat endpoint + per-project signature verification and bridge client.
2026-02-20: Added runtime bridge contract doc at `apiserver/docs/saas-bridge-runtime.md`.
2026-02-20: Added priority-sorted launch task list in `.codex/todo.md` with P0/P1/P2 buckets.
2026-02-20: Ran production readiness sanity checks:
 - `client`: `npm run build` passed (Nuxt build success, chunk-size warnings only).
 - `apiserver`: `composer test` has 1 failing test (`Tests\Feature\Api\SaasProjectInsightsTest`) due to duplicate `products.slug` (`shopcore-commerce-api`) in test data setup.
2026-02-20: Current state is near-ready but not declared 100% bug-free because backend suite is not fully green yet.
