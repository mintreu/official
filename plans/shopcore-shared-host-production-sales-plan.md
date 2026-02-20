# Shopcore Shared-Host Production Sales Plan

Date: 2026-02-19  
Scope: Shared host (`public_html` only), multi-env API deploy, sellable Nuxt ZIP distribution, license + credential hardening, multi-client personalization.

---

## 1) Target Business Model

Goal:
- Mintreu Official theke Shopcore sell hobe as API + Nuxt package.
- Buyer nijer domain e Nuxt static deploy korbe.
- API Mintreu-hosted thakbe (managed), ar demo/testing endpoint o thakbe.
- Product "try before buy" flow thakbe:
  - demo admin login
  - demo user login
  - guest public pages browse

Core sale modes:
1. Managed API + managed demo sandbox.
2. Optional white-label frontend ZIP (SSR false static build).
3. Personalized vertical pack (dress-only, electronics-only, etc).

---

## 2) Shared Host Folder Design (Final)

Base:
`/home/23423432/arokichu/mintreu.com/public_html/`

```txt
public_html/
  .htaccess
  default.php

  official/
    live/                           # existing official public/static assets

  _platform/
    account_gateway/                # optional gateway docs/auth handoff pages

  _api/
    shopcore/
      production/                   # full Laravel project
        public/
      demo/                         # full Laravel project
        public/
      staging/                      # full Laravel project
        public/

  _frontend_demos/
    shopcore_demo_live/             # sales demo frontend build
    shopcore_staging_live/          # QA preview build

  _downloads/
    shopcore/
      stable/                       # paid ZIP packages
      demo/                         # trial ZIP packages (limited)
      manifests/                    # package manifest + checksums
```

Design rules:
- `public/` chara kono Laravel internal file direct web-root e expose kora jabe na.
- Per env (`production/demo/staging`) full isolated code + `.env` + DB.
- `_downloads` e directly listable browsing off korte hobe (`Options -Indexes` equivalent).

---

## 3) Domain and Subdomain Map

Primary plan:
1. `shopcore.mintreu.com` -> `public_html/_api/shopcore/production/public`
2. `demo-shopcore.mintreu.com` -> `public_html/_api/shopcore/demo/public`
3. `staging-shopcore.mintreu.com` -> `public_html/_api/shopcore/staging/public`
4. `try-shopcore.mintreu.com` -> `public_html/_frontend_demos/shopcore_demo_live`

If `account.mintreu.com` already mandatory:
- Keep license/account management there.
- Shopcore API only business routes serve korbe.
- Frontend app e auth redirect allow-list:
  - `account.mintreu.com` (license/session issue)
  - return URL to buyer domain.

Suggested "clever" gap-reduction technique:
- One central issuer: `account.mintreu.com` for license mint + token exchange.
- All API domains trust issuer public key (JWT/JWS verification).
- Ete per action 2-domain dependency thakleo API server e local token verification possible, so every request e cross-domain call lagbe na.

---

## 4) Git Deploy Matrix (Separate Per Target)

Single repo hole recommended branch map:
1. `main` -> `public_html/_api/shopcore/production`
2. `demo` -> `public_html/_api/shopcore/demo`
3. `staging` -> `public_html/_api/shopcore/staging`
4. `frontend-demo` -> `public_html/_frontend_demos/shopcore_demo_live`
5. `release-packages` -> `public_html/_downloads/shopcore/stable` (artifact upload only)

Better enterprise setup (preferred):
1. `shopcore-api` repo -> env-specific deploy targets.
2. `shopcore-nuxt` repo -> demo/static bundle + build artifacts.
3. `shopcore-distribution` repo -> docs/license/readme/package manifests.

Hard guardrails:
- Each pipeline e fixed path lock.
- "no overwrite outside target root" script check.
- Deploy user permissions scoped by path.

---

## 5) Environments and Credentials

Per environment separate:
1. DB database + DB user
2. `.env` file
3. `APP_KEY`
4. cache/session prefix
5. API signing keys
6. queue/log channels

Naming convention:
- `SHOPCORE_ENV=production|demo|staging`
- API keys:
  - `sk_live_*` (production)
  - `sk_demo_*` (demo)
  - `sk_stage_*` (staging)

Demo safety:
- low rate limit
- payment write optional disabled/sandbox-only
- scheduled demo data reset

---

## 6) Sales Demo Experience (Before Purchase)

Expose these public assets:
1. Demo URL (`try-shopcore.mintreu.com`)
2. Demo vendor/admin credentials
3. Demo user credentials
4. Guest browsing flow
5. Feature comparison: demo vs paid

Demo flow:
1. Visitor tries guest pages.
2. Visitor logs as demo user for checkout/profile flow.
3. Visitor logs as demo admin for catalog/order/settings preview.
4. CTA: "Buy License / Get Full Package".

Security for demo credentials:
- rotate weekly or on-demand.
- automated reset script.
- no real payment keys in demo.

---

## 7) Nuxt ZIP Distribution Model (SSR false)

Buyer gets ZIP package:
1. `dist/` static files
2. `README.md`
3. `LICENSE.txt`
4. `SETUP.md` (domain + API base URL config)
5. `PANEL_GUIDE_ADMIN.md`
6. `PANEL_GUIDE_USER.md`
7. `FLOW_MAP.md` (guest->user->vendor flow)
8. `CHANGELOG.md`

Config strategy:
- initial `site.config.json` editable.
- first install/connect e config gets "locked" into signed artifact.

Recommended file set after first activation:
1. `site.public.json` (non-sensitive, human-readable)
2. `site.secure.dat` (encrypted/obfuscated bundle)
3. `license.jwt` (short/medium expiry + refresh mechanism)

---

## 8) License + Credential Hardening Plan

Important:
- Browser side fully secret-safe possible na; tai design hobe "damage minimization + short-lived credential".

Activation flow (first-time setup from Nuxt side):
1. Admin enters:
  - license key
  - owner email
  - site domain
2. Nuxt calls `account.mintreu.com` activation API.
3. Issuer validates purchase + domain binding.
4. Issuer returns:
  - public runtime config
  - encrypted secure blob
  - signed license token
5. Nuxt stores signed artifacts and removes plain editable sensitive data.

Runtime flow:
1. Nuxt boot -> reads `site.public.json`.
2. For privileged actions, exchanges token with `shopcore.mintreu.com`.
3. API validates signature + domain + plan + expiry.
4. If tampered/revoked -> graceful block + re-activate screen.

Anti-tamper controls:
1. Domain allow-list in license.
2. Build fingerprint/hash match.
3. Key rotation endpoint.
4. Revocation list check (cached + periodic refresh).
5. Abuse detection (too many domains using one license).

---

## 9) Multi-Client and Personalization Strategy

Need:
- multiple clients
- client-specific demo
- vertical personalization packs

Approach:
1. Base Nuxt core package.
2. Overlay theme packs:
  - `pack-dress`
  - `pack-grocery`
  - `pack-electronics`
3. Feature flags per license:
  - modules on/off
  - panel menu variants
  - checkout/business rule variants

Config layering order:
1. base defaults
2. vertical pack defaults
3. client overrides
4. runtime server flags

Result:
- ek codebase theke many "personalized storefront" sell kora jabe.

---

## 10) Documentation Bundle Standards (Saleable Grade)

Each release e mandatory docs:
1. Installation quickstart (10-min path)
2. cPanel/Hostinger deploy guide
3. API base URLs by env
4. Demo credential policy
5. License activation + recovery
6. Security best practices
7. Update/rollback guide
8. Support SLA + escalation contact

Also include:
- Postman collection or API reference link
- known limitations section
- checklist: "go-live readiness"

---

## 11) Operational Policies

1. Blue/green-like safety in shared host:
   - new release to timestamped folder
   - symlink/switch doc-root where possible
2. Backup policy:
   - DB daily
   - env + license metadata encrypted backup
3. Monitoring:
   - uptime checks for prod/demo/staging
   - error alerts
   - license activation failure alerts
4. Compliance:
   - access logs retain
   - admin action audit trails

---

## 12) Implementation Roadmap

Phase 1 (Infrastructure baseline):
1. finalize folder + subdomain mapping
2. create env-isolated deploy pipelines
3. enforce path-level deployment permissions

Phase 2 (Demo + sales readiness):
1. launch `try-shopcore` frontend demo
2. seed demo admin/user accounts
3. setup reset + credential rotation jobs

Phase 3 (License hardening):
1. build activation endpoint at `account.mintreu.com`
2. implement signed config + secure blob issuance
3. add revocation + domain mismatch handling

Phase 4 (Distribution packaging):
1. generate sellable ZIP with docs/license files
2. add checksum + manifest
3. release channeling (stable/demo)

Phase 5 (Personalization engine):
1. add vertical theme packs
2. client override layer
3. license-driven feature flags

---

## 13) Immediate Next Actions (Practical)

1. Hostinger panel e 4 ta subdomain map finalize koro:
   - `shopcore`, `demo-shopcore`, `staging-shopcore`, `try-shopcore`.
2. API repo te 3-branch deploy scripts lock koro (`main/demo/staging`).
3. Nuxt repo te demo build pipeline + ZIP artifact pipeline split koro.
4. `account.mintreu.com` e activation API contract freeze koro.
5. `site.config.json -> secure artifact` conversion prototype build koro.

