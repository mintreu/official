# Mintreu Official Enterprise Plan (Upgraded)

## Goal and posture
Build a premium, stable, revenue-first digital platform that sells and distributes Mintreu digital products and services. Fast to ship, safe to operate, simple to maintain for a solo founder.

## Non-negotiables
- Golden path must never break: Signup/Login -> Browse -> Checkout -> Payment Webhook -> License/API Key -> Access -> Support
- Kill switches for payments, downloads, licensing, referrals, AI, support
- No physical products, no MLM, no crypto payments
- Minimal, reversible changes; reuse proven code where possible
- Anything touching checkout, licenses, downloads, API access, or support must be feature-flagged and audited

## Business thesis
Grow via a focused, high-trust digital commerce stack with predictable licensing and delivery. Prioritize revenue-producing features first, then reliability, then polish.

## Core product pillars
1) Commerce and licensing core
   - Plans, pricing, discounts, vouchers
   - Checkout, payment webhooks, receipts
   - License issuance + API keys + usage rules

2) Secure distribution
   - Signed, expiring download links
   - Role-based access for products and updates
   - Audit trail for access and changes

3) Customer success engine
   - Helpdesk + FAQ
   - Automated notices (billing, renewal, access)
   - Issue resolution and refunds under explicit approval

4) Growth and retention
   - Referral tracking (single level)
   - Upgrade paths, bundles, and subscriptions
   - Content, templates, and starter kits

## Product portfolio strategy
- Inventory current products and revenue potential
- Pick 1-2 flagship products to drive acquisition
- Upgrade or sunset low-ROI products
- Every new product must map to revenue, retention, or distribution

## Implementation alignment (current reality)
- Download flow is implemented in API (DownloadController + DownloadService + routes)
- ProductResource exists but table/infolist reference deprecated columns
- AdvertisementResource exists but points to the wrong model namespace
- Frontend has no AdZone component or ads API
- StorageProvider/StorageCredential resources exist but tables were dropped

These are prioritized as immediate fixes before growth work.

## Technical roadmap (phased)

### Phase 0: Foundation (0-4 weeks)
- Audit current code, data model, and flows
- Stabilize payment, license, and download pipeline
- Implement audit logging and kill switches
- Define pricing, plans, and product catalog structure

### Phase 1: Launch-ready core (1-3 months)
- Finalize checkout + webhook + license issuance flow
- Build customer dashboard (licenses, downloads, API keys)
- Add vouchers and referrals
- Ship minimal helpdesk + FAQ

### Phase 2: Scale-ready platform (3-9 months)
- Usage tracking and rate limits
- Tiered access and subscription renewal automation
- Upgrade/downgrade paths and bundle management
- Harden monitoring, alerts, and incident playbook

### Phase 3: Growth acceleration (9-18 months)
- Public docs and developer onboarding
- Marketing funnel improvements
- Affiliate partnerships and B2B licensing
- Optional AI assist features behind feature flags

## Metrics and KPIs
- MRR and churn
- Activation rate (signup -> first purchase)
- License issuance success rate
- Download success and retry rate
- Refund rate and dispute rate
- Support response and resolution time

## Risk controls
- Fraud prevention and webhook verification
- Rate limits on API keys and downloads
- Manual approval gates for refunds and pricing changes
- Backups and recovery tests

## Required inputs from founder
- Current product list with status and revenue
- Target customer profiles and price sensitivity
- Payment provider constraints and refund policy
- Priority markets and compliance concerns

## Execution cadence
- Weekly: revenue KPIs, checkout health, support backlog
- Monthly: product pruning, pricing tests, roadmap re-ranking
- Quarterly: infrastructure review, security review, growth review

## Immediate fixes (P0)
1) Update ProductResource table/infolist to match refactored schema
2) Fix AdvertisementResource model namespace
3) Decide whether to remove or hide StorageProvider/StorageCredential resources
4) Add ads API + AdZone component (if ads are a near-term revenue channel)

## Next actions (immediate)
1) Inventory all current products and rank by revenue potential
2) Choose flagship product(s) and define launch scope
3) Lock the golden path tests and alerting
4) Ship the minimal purchase -> license -> access flow

## Notes
This plan is the active enterprise direction for Mintreu Official. All legacy marketplace/PaaS plans must be reviewed and archived if they conflict with this direction.
