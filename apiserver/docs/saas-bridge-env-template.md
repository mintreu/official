# SaaS Bridge .env Templates (Official + Child)

Use these exact variables to enable dual validation and real-time insights.

## 1) Official (`official/apiserver/.env`)

```env
# Internal signed bridge auth (child -> official)
MINTREU_SAAS_INTERNAL_KEY=replace_with_strong_key
MINTREU_SAAS_INTERNAL_SECRET=replace_with_strong_secret
MINTREU_SAAS_MAX_SKEW_SECONDS=300

# Global fallback license credential (optional)
MINTREU_SAAS_LICENSE_KEY=
MINTREU_SAAS_LICENSE_SECRET=

# Project-specific credentials (recommended)
MINTREU_SAAS_SHOPCORE_LICENSE_KEY=shopcore_license_key_here
MINTREU_SAAS_SHOPCORE_LICENSE_SECRET=shopcore_license_secret_here

MINTREU_SAAS_HELPDESK_LICENSE_KEY=helpdesk_license_key_here
MINTREU_SAAS_HELPDESK_LICENSE_SECRET=helpdesk_license_secret_here
```

## 2) Child (`shopcore/apiserver/.env`)

```env
# Project identity (required, no fallback)
MINTREU_PROJECT_KEY=shopcore
SAAS_PRODUCT_SLUG=shopcore-commerce-api

# Site license credentials sent to official license/check
MINTREU_SITE_LICENSE_KEY=shopcore_license_key_here
MINTREU_SITE_LICENSE_SECRET=shopcore_license_secret_here

# Official bridge endpoint
MINTREU_OFFICIAL_BASE_URL=http://localhost:8000
MINTREU_OFFICIAL_SITE_STATS_PATH=/api/internal/saas/site-stats
MINTREU_OFFICIAL_API_KEY=replace_with_same_as_MINTREU_SAAS_INTERNAL_KEY
MINTREU_OFFICIAL_WEBHOOK_SECRET=replace_with_same_as_MINTREU_SAAS_INTERNAL_SECRET
MINTREU_OFFICIAL_TIMEOUT=10

# License endpoint (same official)
MINTREU_LICENSE_BASE_URL=http://localhost:8000
MINTREU_LICENSE_CHECK_PATH=/api/internal/saas/license/check
MINTREU_LICENSE_API_KEY=replace_with_same_as_MINTREU_SAAS_INTERNAL_KEY
MINTREU_LICENSE_SECRET=replace_with_same_as_MINTREU_SAAS_INTERNAL_SECRET
MINTREU_LICENSE_TIMEOUT=10
```

## 3) Scheduler (child)

```php
Schedule::command('saas:license-check')->everyFifteenMinutes()->withoutOverlapping();
Schedule::command('saas:stats-snapshot --minutes=5')->everyFiveMinutes()->withoutOverlapping();
Schedule::command('saas:stats-push --limit=200')->everyMinute()->withoutOverlapping();
```

## 4) Validation Rule

A child API request is accepted only when:
1. internal signature is valid (`X-Mintreu-*` headers), and
2. project-specific `license_key + license_secret` are valid.

If either fails, official returns non-success and child should treat license as invalid/error.
