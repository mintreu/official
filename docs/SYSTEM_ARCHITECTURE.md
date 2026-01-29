# Mintreu Digital Product Platform - System Architecture (Official)

## Overview
Mintreu Official is the core mothership platform for selling and distributing digital products, SaaS, and API offerings.

Non-negotiables:
- Golden path is sacred: Signup/Login -> Browse -> Checkout -> Payment Webhook -> License/API Key -> Access -> Support
- Feature flags + audit logs for checkout/license/download/API/support changes
- Kill switches for payments, downloads, licensing, referrals, AI, support

Status note:
Migrations are the source of truth. This document is aligned to current migrations and code as of 2026-01-29.

---

## Core Data Model (Current)

### Products
Key columns:
- id, slug, title, description, content, image
- short_description
- price (decimal)
- category, type, version, status, featured
- downloads, rating
- demo_url, github_url, documentation_url
- requires_auth (boolean)
- default_license (string)
- meta (json)
- api_config (json)

### Product Sources
Unified download sources.
Key columns:
- product_id (FK)
- provider, name, description
- source_url, encrypted_token
- version, file_name, file_size
- metadata (json)
- is_primary, is_active
- last_verified_at

### Licenses
Key columns:
- product_id, user_id
- type, usage_count, max_usage
- meta (json)
- api_credential_id (FK)
- plan_id (FK)
- server_info (json)

### Download Logs
Key columns:
- product_id, product_source_id, user_id, license_id
- ip_address, user_agent, status
- download_token, file_size, downloaded_at

### External API Credentials
Key columns:
- product_id, user_id
- encrypted_api_key, encrypted_api_secret
- external_user_id, external_account_url
- plan_id, rate_limits, usage_stats, meta
- is_active, last_synced_at, expires_at

### Plans + API Licensing
plans, api_endpoints, api_keys, api_usage_logs support API subscriptions.

---

## Filament Admin Panel (Current)
- ProductResource exists and uses SourcesRelationManager.
- AdvertisementResource exists (check model namespace alignment).
- StorageProviderResource + StorageCredentialResource still exist but their tables were dropped in refactor.

Known gaps are tracked in /issues.

---

## Download Flow (Implemented)
Routes and controller are wired:
- POST /api/products/{slug}/download
- GET /api/download/{token}
- GET /api/products/{slug}/sources

DownloadService generates masked URLs and logs download activity.

---

## Frontend Integration (Nuxt)
- Product pages should call initiate-download and open returned URL.
- License modal should show key and attribution if required.
- Ad zones are not yet implemented in the frontend.

---

## Advertisements
- advertisements table exists.
- Filament admin resource exists.
- No public API endpoint or frontend AdZone component yet.

---

## Known Gaps (Tracked in /issues)
- ProductResource table/infolist still reference removed columns.
- AdvertisementResource model namespace mismatch (if not corrected).
- Frontend AdZone missing.
- StorageProviders/StorageCredentials resources still exist after table removal.

---

## Next Implementation Focus
1) Align Filament product listings with refactored schema
2) Fix AdvertisementResource model namespace + add ads API and frontend AdZone
3) Remove or hide obsolete admin resources (storage providers/credentials)
4) Add tests for golden path (checkout -> license -> access)
