# Official 6-Month 10 Lakh Execution Plan

Date: 2026-02-19  
Owner: Mintreu Official (mothership)

## Quick Navigation
1. [Operating Model](#operating-model)
2. [Plan Governance Model](#plan-governance-model)
3. [Revenue Target Breakdown](#revenue-target-breakdown)
4. [Launch Week Operating Playbook](#launch-week-operating-playbook)
5. [Offer and Promotion Framework](#offer-and-promotion-framework)
6. [Project Pipeline and Product Expansion](#project-pipeline-and-product-expansion)
7. [Weekly KPI Scoreboard](#weekly-kpi-scoreboard)

## Operating Model
1. `official` sells and distributes all products.
2. Customer buys:
   - API subscription (recurring)
   - Nuxt frontend product (one-time)
3. Backend source code is never sold.
4. One API powers many differentiated frontend products.
5. Portfolio must show only real build-and-sell products.

## Plan Governance Model
Goal: maintain one master planning brain while allowing per-project and per-product limits.

Final structure:
1. `official/plans/core/`
   - global defaults: pricing guardrails, quota policy, trial policy, promotion rules.
2. `official/plans/projects/<project>.json`
   - project-level overrides: Shopcore vs Helpdesk plan differences.
3. `official/plans/products/<product-slug>.json`
   - optional product-level overrides for special bundles.
4. Final applied plan (priority order):
   - `core defaults -> project override -> product override`.
5. Child APIs consume versioned plan snapshots from official:
   - include `plan_version`, `effective_from`, and `rollback_to`.

This keeps maintenance low and avoids hardcoding limits separately in each child repo.

Implementation reference:
1. `plans/projects/official-to-child-plan-propagation.md`

## Revenue Target Breakdown
Total goal in 6 months: `10,00,000`.

Monthly target:
1. Month 1: `80,000`
2. Month 2: `110,000`
3. Month 3: `145,000`
4. Month 4: `190,000`
5. Month 5: `220,000`
6. Month 6: `255,000`

Revenue mix target:
1. API subscriptions: 60%
2. Frontend product sales: 30%
3. Setup/customization/onboarding: 10%

## Launch Week Operating Playbook
P0 (must ship this week):
1. Live pages for:
   - `shopcore-commerce-api`
   - `helpdesk-support-api`
   - 30 frontend products (20 commerce + 10 support)
2. Every frontend product page must show required API dependency.
3. Payment, license, and product access flow must pass full test.
4. Demo URLs and CTA copy must be visible on all hero/product pages.
5. Support intake and SLA expectation must be published.

P1 (next 7-14 days):
1. Bundle pages:
   - Commerce API + 1 frontend product
   - Support API + 1 frontend product
2. Add onboarding upsell options.
3. Add objection-handling FAQ from first buyer conversations.

## Offer and Promotion Framework
Use three copy angles in every campaign:
1. Speed angle:
   - "Go live in days, not months."
2. Control angle:
   - "One API. Multiple products. Lower maintenance."
3. Revenue angle:
   - "Launch niche products fast and scale catalog without backend rebuild."

Product page CTA rules:
1. Primary CTA: `Start API Subscription`
2. Secondary CTA: `View Compatible Products`
3. Dependency note: `This frontend requires <api-slug>`
4. Trust note: `Managed infrastructure, no backend ownership needed`

## Project Pipeline and Product Expansion
Current live API projects:
1. `shopcore-commerce-api` (PulseCart Commerce Cloud API)
2. `helpdesk-support-api` (HelpdeskFlow Support API)

Current live frontend products:
1. 20 commerce products (Shopcore family)
2. 10 support products (Helpdesk family)

Catalog detail:
1. `plans/projects/production-product-catalog.md`

Next API projects to build:
1. `LicenseOps Activation Cloud`
2. `NotifyStack Messaging Cloud`
3. `FlowBridge Automation Cloud`

Rule:
1. New API cannot be launched unless it has at least 2 sellable frontend products.

## Weekly KPI Scoreboard
Track every week:
1. New API subscribers by family (Shopcore/Helpdesk)
2. Frontend units sold by product slug
3. Bundle attach rate
4. Demo-to-paid conversion
5. Churn and renewal count
6. Support load vs resolution time
7. Ad spend vs closed revenue

Non-negotiable:
1. No fake metrics.
2. No fake case studies.
3. No product entries outside real sellable scope.
