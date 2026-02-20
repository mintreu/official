# SaaS Bridge Runtime Contract

This document defines real production bridge communication between `official` and each child API project.

## 1) Signed Headers (required for internal endpoints)

Every internal request to `official` must include:

- `X-Mintreu-Project`: child project slug (e.g. `shopcore-commerce-api`)
- `X-Mintreu-Key`: internal key for that project
- `X-Mintreu-Timestamp`: unix timestamp (seconds)
- `X-Mintreu-Signature`: HMAC SHA-256

Signature base string:

`{timestamp}.{HTTP_METHOD}.{request_path_without_leading_slash}.{raw_json_body}`

Secret: project-specific internal secret.

## 2) Child -> Official endpoints

### Heartbeat

`POST /api/internal/saas/project/heartbeat`

Payload example:

```json
{
  "machine": {
    "host": "shopcore-node-01",
    "os": "linux",
    "kernel": "6.5.0",
    "cpu": "4 vCPU",
    "memory_total_mb": 4096,
    "memory_used_mb": 2360
  },
  "runtime": {
    "node": "20.10.0",
    "nuxt": "3.15.1",
    "app_version": "2026.02.20"
  },
  "health": {
    "status": "ok",
    "version": "v1"
  }
}
```

### Site telemetry

`POST /api/internal/saas/site-stats`

Payload example:

```json
{
  "site_id": 81,
  "site_uuid": "33333333-3333-3333-3333-333333333333",
  "site_slug": "velori-live",
  "vendor_id": 101,
  "window_start": "2026-02-20T09:00:00Z",
  "window_end": "2026-02-20T10:00:00Z",
  "metrics": {
    "orders_count": 9,
    "new_users_count": 2,
    "revenue_paise": 149000,
    "requests_count": 220,
    "errors_count": 1
  },
  "machine": {
    "host": "shopcore-node-01",
    "os": "linux",
    "memory_used_mb": 2360
  },
  "runtime": {
    "node": "20.10.0",
    "nuxt": "3.15.1"
  }
}
```

## 3) Official -> Child endpoints

### Vendor/site provision

`POST {child_base_url}/api/internal/saas/vendors/provision`

Automatically triggered from subscription + add-site flow.

### Child health ping

`GET {child_base_url}/api/health`

Triggered from official dashboard endpoint:

- `POST /api/saas/projects/{project}/ping`

## 4) Official user dashboard endpoints

- `GET /api/saas/sites`
- `POST /api/saas/licenses/{license}/sites`
- `GET /api/saas/sites/{siteUuid}`
- `GET /api/saas/insights/overview`
- `GET /api/saas/insights/projects/{project}`
- `GET /api/saas/insights/vendors/{vendorId}`
- `GET /api/saas/projects`
- `GET /api/saas/projects/{project}`
- `POST /api/saas/projects/{project}/ping`

## 5) Storage and audit

- `licensed_sites`: canonical mapping of user license -> provisioned child site.
- `saas_site_stat_events`: incoming real telemetry.
- `saas_projects`: project registry + last heartbeat snapshot.
- `saas_sync_logs`: inbound/outbound bridge logs for debugging and audit.
