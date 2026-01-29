# Issue 6: Obsolete Admin Resources for Removed Tables

**Severity:** Medium
**Status:** Open

---

### Summary
StorageProviderResource and StorageCredentialResource still exist, but their tables were dropped in the refactor.

### Evidence
- Resources exist under apiserver/app/Filament/Resources/StorageProviders and StorageCredentials
- Tables dropped in migration 2026_01_17_000002_drop_deprecated_tables.php

### Impact
Admin UI may error or expose dead screens.

### Proposed Solution
Remove or hide these resources, or reintroduce tables only if required.
