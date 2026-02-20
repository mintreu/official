# Shopcore Laragon Local Mirror Plan

Date: 2026-02-19  
Scope: Local Windows + Laragon setup so hostinger structure (`public_html` based) and local structure stay similar, with multiple `.test` domains and envs.

---

## 1) Objective

Need:
1. Local theke run korte hobe:
   - `mintreu.test`
   - `shopcore.mintreu.test`
   - `demo-shopcore.mintreu.test`
   - `staging-shopcore.mintreu.test`
   - `try-shopcore.mintreu.test`
2. Hostinger path model ar local path model maximum same rakhte hobe.
3. Demo/production/staging API and demo frontend alada domain e test korte hobe.

---

## 2) Local Folder Structure (Mirror of Shared Host)

Recommended root:
`C:\laragon\www\mintreu\hosting-mirror\mintreu.com\public_html\`

```txt
public_html\
  .htaccess
  default.php

  official\
    live\

  _api\
    shopcore\
      production\      # full Laravel project copy/symlink
        public\
      demo\
        public\
      staging\
        public\

  _frontend_demos\
    shopcore_demo_live\
    shopcore_staging_live\

  _downloads\
    shopcore\
      stable\
      demo\
      manifests\
```

Parity rule:
- Hostinger e je path pattern use hobe, local eo oi same relative pattern thakbe (`public_html/_api/shopcore/<env>/public`).

---

## 3) Source Code Mapping Strategy

Current repo structure:
- `apiserver\` (Laravel)
- `client\` (Nuxt)

Local mirror e 2 way:

Option A (recommended): deploy-copy style
1. Build script apiserver theke env folder e sync korbe.
2. Nuxt generate output `_frontend_demos` e sync korbe.
3. Hostinger deployment behavior accurately mirror hobe.

Option B: symlink style
1. `production/demo/staging` folder e symlink use.
2. Fast iteration possible.
3. But real hostinger deploy mismatch hote pare.

Use Option A for final QA, Option B for fast dev.

---

## 4) Local Domains and Hosts File

Edit Windows hosts file:
`C:\Windows\System32\drivers\etc\hosts`

Add:
```txt
127.0.0.1 mintreu.test
127.0.0.1 shopcore.mintreu.test
127.0.0.1 demo-shopcore.mintreu.test
127.0.0.1 staging-shopcore.mintreu.test
127.0.0.1 try-shopcore.mintreu.test
```

If you need old naming compatibility:
```txt
127.0.0.1 demo.mintreu.test
127.0.0.1 subdomain.mintreu.test
```

---

## 5) Laragon Apache VirtualHost Plan

Create/update Laragon Apache vhost config (example):
`C:\laragon\bin\apache\httpd-2.4.x\conf\extra\httpd-vhosts.conf`

Map domains:
1. `mintreu.test` -> `...\public_html`
2. `shopcore.mintreu.test` -> `...\public_html\_api\shopcore\production\public`
3. `demo-shopcore.mintreu.test` -> `...\public_html\_api\shopcore\demo\public`
4. `staging-shopcore.mintreu.test` -> `...\public_html\_api\shopcore\staging\public`
5. `try-shopcore.mintreu.test` -> `...\public_html\_frontend_demos\shopcore_demo_live`

Minimal vhost template:
```apache
<VirtualHost *:80>
    ServerName shopcore.mintreu.test
    DocumentRoot "C:/laragon/www/mintreu/hosting-mirror/mintreu.com/public_html/_api/shopcore/production/public"
    <Directory "C:/laragon/www/mintreu/hosting-mirror/mintreu.com/public_html/_api/shopcore/production/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Repeat for demo/staging/try domains.

---

## 6) Environment File Design

Maintain separate env files:
1. `_api/shopcore/production/.env`
2. `_api/shopcore/demo/.env`
3. `_api/shopcore/staging/.env`

Key separation:
1. `APP_ENV`
2. `APP_KEY`
3. `DB_DATABASE`
4. `CACHE_PREFIX`
5. `SESSION_COOKIE`
6. API signing secrets

Naming suggestion:
- `shopcore_prod`
- `shopcore_demo`
- `shopcore_staging`

---

## 7) Local Databases

Create 3 databases:
1. `shopcore_prod_local`
2. `shopcore_demo_local`
3. `shopcore_staging_local`

Seed rules:
1. demo DB: predefined demo admin/user accounts.
2. staging DB: QA data.
3. prod-local DB: production-like sanitized data.

Demo credential policy:
- fixed credentials for sales testing
- scheduled reset command

---

## 8) Local Build and Sync Flow

API sync workflow:
1. pull latest repo
2. prepare env-specific copies (`production/demo/staging`)
3. run composer install per env (or shared vendor strategy if managed safely)
4. run migrations per env DB

Frontend sync workflow:
1. build Nuxt static (`ssr: false`, `generate`)
2. copy output:
   - demo -> `_frontend_demos/shopcore_demo_live`
   - staging -> `_frontend_demos/shopcore_staging_live`

Package test workflow:
1. generate ZIP artifact from prepared static folder
2. include docs/license/readme
3. test activation flow with local issuer mock

---

## 9) Hostinger-Local Parity Checklist

Before release, verify:
1. same route paths on local and hostinger
2. same domain structure (prod/demo/staging)
3. same folder depth under `public_html`
4. same env split logic
5. same demo login behavior
6. same license activation flow endpoints

Golden rule:
- Local test pass only accepted if it uses same folder pattern as hostinger target.

---

## 10) Suggested Automation (Agent-Managable)

Create scripts in repo:
1. `scripts/local/setup-hosts.md` (manual guide; hosts file needs admin)
2. `scripts/local/sync-api-env.ps1`
3. `scripts/local/build-demo-frontend.ps1`
4. `scripts/local/reset-demo-data.ps1`
5. `scripts/local/package-zip.ps1`

What agent can fully manage:
1. folder generation
2. vhost template file generation
3. env template generation
4. build/sync scripts
5. docs + checklist updates

Needs manual admin step:
1. hosts file edit
2. Laragon restart

---

## 11) 3-Stage Execution Plan

Stage 1 (Today):
1. create local mirror folders
2. create Apache vhost entries
3. map hosts file
4. verify all `.test` domains open

Stage 2:
1. wire production/demo/staging API instances
2. seed demo credentials
3. publish `try-shopcore` demo frontend locally

Stage 3:
1. run end-to-end sales flow (guest/user/admin)
2. run activation/license hardening flow
3. produce sale ZIP and test in clean local folder

---

## 12) Immediate Next Commands (When Implementation Starts)

1. Create mirror directories under `C:\laragon\www\mintreu\hosting-mirror\...`
2. Add Apache vhost file entries for five domains.
3. Restart Laragon Apache.
4. Copy/sync `apiserver` to production/demo/staging mirrors.
5. Build `client` and publish demo static output.

