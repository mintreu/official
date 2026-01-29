# Issue 5: AdvertisementResource Model Namespace Mismatch

**Severity:** High
**Status:** Open

---

### Summary
AdvertisementResource points to App\\Models\\Advertisement, but the model is App\\Models\\Content\\Advertisement.

### Evidence
- Resource: apiserver/app/Filament/Resources/Advertisements/AdvertisementResource.php
- Model: apiserver/app/Models/Content/Advertisement.php

### Impact
Filament resource will fail to load or will reference a non-existent model.

### Proposed Solution
Update AdvertisementResource to use App\\Models\\Content\\Advertisement.
