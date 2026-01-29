# Issue 2: Download Flow Wired (Resolved)

**Severity:** High (Resolved)
**Status:** Resolved

---

### Summary
The download flow is implemented and connected to API routes.

### Evidence
- DownloadService exists at: apiserver/app/Services/DownloadService.php
- DownloadController exists at: apiserver/app/Http/Controllers/Api/DownloadController.php
- Routes are wired in apiserver/routes/api.php:
  - POST /api/products/{slug}/download
  - GET /api/download/{token}
  - GET /api/products/{slug}/sources

### Follow-ups
- Add frontend download wiring and license UI as needed (tracked elsewhere).
