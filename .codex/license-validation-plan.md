# License validation plan (multi-domain / resale control)

Goal: prevent license misuse when a client tries to reuse or resell on another domain/project.

## Core approach
- Issue license per purchase with usage limits + domain binding.
- Validate license at runtime from the client project back to Official API.
- Deny or flag when domain mismatch / exceeded limits / expired.

## License model additions
- licensed_domains: JSON array (allowed domains)
- domain_limit: int (max domains)
- domain_change_quota: int (allowed replacements per period)
- domain_change_window_days: int (period window)
- usage_count + max_usage (already in licensing)
- project_fingerprint (optional): hash of project + domain + env
- last_verified_at

## Validation flow (runtime)
1) Client project includes a small SDK/package (internal) that calls Official API.
2) SDK sends: license_key, domain, app_id, project_fingerprint, ip, version.
3) API checks:
   - license exists, active, not expired
   - domain allowed OR domain_limit not exceeded (auto-attach if allowed)
   - if domain change: ensure change quota not exceeded
   - usage_count < max_usage
   - optional project_fingerprint matches (if bound)
4) API returns signed token (short TTL) or deny reason.
5) Client caches token and periodically re-validates.

## Anti-resale heuristics
- Domain allowlist + domain_limit
- Domain change quota + cooldown (prevent frequent swaps)
- IP/ASN anomaly checks (optional)
- Rate limit validation failures
- Manual revoke or suspend license

## Domain change / replace flow
Goal: allow legitimate domain migration without manual support every time.

Suggested API:
- POST /api/licenses/{license_key}/change-domain
  - requires auth or signed token
  - payload: old_domain, new_domain, reason

Rules:
- allow if old_domain is in licensed_domains
- remove old_domain, add new_domain
- decrement domain_change_quota for current window
- log change with IP + user agent
- optional admin override to reset quota

## Internal package/SDK (recommended)
- Create a tiny package (PHP + JS) used by all distributed products:
  - validateLicense(licenseKey, domain, fingerprint)
  - cache token
  - enforce denial if validation fails
- This keeps license checks consistent across all products.

## SaaS / API product
- API key tied to License + plan.
- Each request validates API key and license status.
- If license revoked/expired: reject API requests.

## Notes
- Do not block offline dev: allow localhost domains by default.
- Provide admin override (manual domain attach).
- Always log validation attempts for audit.
