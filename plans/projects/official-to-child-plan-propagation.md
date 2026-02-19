# Official -> Child Plan Propagation

Date: 2026-02-19  
Status: Recommended architecture

## Objective
Keep one master planning source in `official` while allowing project/product-specific limits in child APIs.

## Source of Truth
`official` is the only plan authoring repo.

Child projects (`shopcore`, `helpdesk`, future APIs) must consume plan snapshots, not invent local plan logic.

## Configuration Layers
1. `plans/core/*.json`
   - global defaults (pricing floor/ceiling, quota rules, trial policy, feature flags).
2. `plans/projects/<project>.json`
   - project overrides (Shopcore vs Helpdesk API behavior).
3. `plans/products/<product-slug>.json`
   - optional product overrides (special bundle limits, launch promos).

Resolution order:
1. core defaults
2. project overrides
3. product overrides

## Versioning Rules
1. Every exported plan snapshot includes:
   - `plan_version`
   - `effective_from`
   - `generated_at`
2. Child project stores:
   - `current_plan_version`
   - `last_known_good_version`
3. On invalid plan sync:
   - keep serving `last_known_good_version`
   - raise alert event to official.

## Delivery Options
1. Pull model:
   - child API fetches signed plan JSON on deploy/startup and caches locally.
2. Push model:
   - official webhook triggers child to refresh and validate plan snapshot.

Recommended start:
1. Pull on deploy + daily cron refresh.

## Safety Rules
1. Hard validation schema required before applying any plan update.
2. Unknown fields ignored; missing required fields fail validation.
3. Plan changes must be auditable (who changed, when, old value, new value).

## Why this works for your business
1. One control center for pricing and limits.
2. Project-level flexibility without config duplication.
3. Faster launch of new child APIs and product families.
4. Lower maintenance load for solo-founder operation.
