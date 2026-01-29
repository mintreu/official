# Issue 3: Advertisement Module Partially Implemented

**Severity:** Medium
**Status:** Open

---

### Summary
The admin resource exists, but public API + frontend AdZone are missing.

### Evidence
- Filament resource exists: apiserver/app/Filament/Resources/Advertisements/AdvertisementResource.php
- No AdZone component or ad fetch logic found in client/ (search for AdZone/ads_* returned none)
- No public API route for fetching active ads

### Impact
No ads are shown on the frontend, so monetization via ads is blocked.

### Proposed Solution
1) Add API endpoint to fetch active ads by placement
2) Build Nuxt AdZone component
3) Insert AdZone into desired layouts
