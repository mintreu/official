# Issue 4: ProductResource Table/Infolist Uses Deprecated Columns

**Severity:** High
**Status:** Open

---

### Summary
Filament ProductResource table and infolist reference columns removed during the schema refactor.

### Evidence
Files:
- apiserver/app/Filament/Resources/Products/Tables/ProductsTable.php
- apiserver/app/Filament/Resources/Products/Schemas/ProductInfolist.php

Deprecated columns referenced:
- download_url
- is_payable
- requires_account
- default_license_type

### Impact
Admin UI shows incorrect or missing data and risks confusion during product management.

### Proposed Solution
Update table and infolist to use current schema:
- requires_auth
- default_license
- remove download_url/is_payable columns
- add meta/api_config visibility if needed
