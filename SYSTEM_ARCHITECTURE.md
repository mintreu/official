# Mintreu Digital Product Distribution & Marketplace System

## Architecture Overview

A production-ready system for managing and distributing digital products with flexible licensing, multi-source downloads, and advertisement monetization.

---

## Database Models & Schema

### Core Models

#### 1. **Product** (Enhanced)
Main marketplace product model supporting all digital asset types.

```
Products Table
├─ Basic: id, slug, title, description, content, image
├─ Pricing: price (decimal), is_payable (boolean)
├─ Metadata: type (template|api|package|media|game|plugin), category, version
├─ Publishing: status (draft|published|archived), featured
├─ License: default_license_type, requires_account
├─ Tracking: downloads, rating
└─ Relationships: configs, resources, licenses, meta, downloadLogs
```

**Product Types**:
- **template**: HTML/Vue/React/Next.js/Nuxt templates
- **api**: API packages & subscriptions
- **package**: Code packages & libraries
- **media**: Icons, fonts, images, designs
- **game**: Browser-based games (playable instantly)
- **plugin**: WordPress plugins, custom extensions
- **other**: Miscellaneous digital products

---

#### 2. **ProductConfig**
Multi-source download configuration per product.

```
ProductConfigs Table
├─ source_type: GIT_REPO|LOCAL_STORAGE|GOOGLE_DRIVE|AWS_S3|DROPBOX|EXTERNAL_URL
├─ source_identifier: Repo path, folder ID, URL
├─ storage_credential_id: FK to encrypted credentials (for private repos)
├─ metadata: JSON (provider-specific settings)
├─ is_primary: boolean (default download source)
├─ is_private: boolean (requires authentication)
└─ last_validated_at: timestamp
```

**Multi-Source Strategy**:
- **Free Small Files** → Local Storage (Laravel storage/)
- **Large Files** → AWS S3 / CDN
- **Code/Releases** → GitHub / Bitbucket releases
- **Media** → Google Drive / Local Storage
- **External** → Direct URLs

---

#### 3. **ProductResource**
Variants/components of products (source code, binary, demo, etc).

```
ProductResources Table
├─ product_id: FK
├─ product_config_id: FK (which storage)
├─ name: Resource name (Source Code, Binary, Demo)
├─ resource_type: MAIN|DOCUMENTATION|DEMO|MEDIA|SOURCE
├─ download_limit: Null=unlimited, 1=single-use, 3,5,10 for commercial
├─ requires_auth: boolean
├─ is_commercial_only: boolean (demo only with paid license)
└─ description: text
```

---

#### 4. **License**
Comprehensive licensing system with usage tracking.

```
Licenses Table
├─ product_id, product_resource_id, user_id (nullable for guests)
├─ license_key: Unique identifier (MINTREU-XXXXX)
├─ license_type: Enum (see below)
├─ usage_count: Current usage count
├─ max_usage: Max allowed uses
├─ email: Associated email
├─ attribution_text: Required attribution
├─ api_config: JSON for API subscriptions
├─ expires_at, is_active
├─ first_used_at, last_used_at
└─ usage_terms: JSON
```

**License Types**:
```php
- FREE_SINGLE_USE          // 1 use only, non-commercial
- FREE_ATTRIBUTION         // Unlimited, must credit Mintreu
- FREE_UNLIMITED           // Unlimited non-commercial use
- COMMERCIAL_SINGLE_USE    // 1 use in commercial project
- COMMERCIAL_3_USES        // Max 3 commercial uses
- COMMERCIAL_10_USES       // Max 10 commercial uses
- API_SUBSCRIPTION         // Rate-limited API access
- DEMO                     // 30-day demo access
```

---

#### 5. **StorageProvider** (Dynamic)
Pluggable storage backends. Add new providers without code changes.

```
StorageProviders Table
├─ name: GITHUB, BITBUCKET, GOOGLE_DRIVE, AWS_S3, DROPBOX, LOCAL_STORAGE
├─ display_name: Human-readable name
├─ description: What this provider does
├─ config_schema: JSON Schema (validates required fields)
├─ is_active: boolean
├─ rate_limit: API rate limit per hour
├─ webhook_secret: For validating webhooks
└─ icon: Icon class or URL

Pre-seeded providers:
1. LOCAL_STORAGE  - Laravel storage/
2. GITHUB         - Releases from GitHub
3. BITBUCKET      - Bitbucket downloads
4. GOOGLE_DRIVE   - Drive folder sharing
5. AWS_S3         - S3 bucket storage
6. DROPBOX        - Dropbox folder
7. EXTERNAL_URL   - Direct links
```

---

#### 6. **StorageCredential**
Encrypted credentials for private repositories.

```
StorageCredentials Table
├─ name: Friendly name
├─ storage_provider_id: FK
├─ encrypted_token: Crypt::encryptString()
├─ account_identifier: Account name/ID
├─ metadata: JSON (provider-specific)
├─ is_active: boolean
└─ last_verified_at: timestamp
```

**Security**: Tokens encrypted with Laravel's Crypt facade.

---

#### 7. **DownloadLog**
Audit trail for all downloads.

```
DownloadLogs Table
├─ product_id, product_resource_id, license_id, user_id (nullable)
├─ ip_address: Visitor's IP
├─ user_agent: Browser info
├─ status: pending|completed|failed
├─ download_token: For resumable downloads
├─ file_size: Bytes downloaded
├─ checksum: SHA256 for integrity
├─ downloaded_at: When download occurred
└─ Analytics: Track trends, popular items
```

---

#### 8. **ProductMeta**
Flexible metadata for product-specific attributes.

```
ProductMeta Table
├─ product_id, key (indexed), value (JSON)
├─ One key-value pair per row
└─ Examples:
   ├─ {key: 'framework', value: 'Vue 3 + Tailwind'}
   ├─ {key: 'browser_compatible', value: ['Chrome', 'Firefox', 'Safari']}
   ├─ {key: 'file_format', value: 'SVG, PNG, PSD'}
   └─ {key: 'android_app_url', value: 'https://play.google.com/...'}
```

---

#### 9. **Advertisement**
Monetization through ad placement management.

```
Advertisements Table
├─ name: Display name for admin
├─ placement: ads_top|ads_sidebar|ads_bottom|ads_insights
├─ html_code: Google AdSense or custom HTML/script
├─ allowed_pages: JSON array (null = all pages)
├─ priority: Display order
├─ impressions: Total views
├─ clicks: Total clicks (trackable ads)
├─ unique_ips: Unique visitors who saw ad
├─ viewed_ips: JSON {ip: [timestamps]} (24-hour tracking)
├─ max_impressions_per_ip: Limit per IP per day (default: 3)
├─ is_active: Publish status
├─ starts_at, ends_at: Campaign dates
└─ Placements:
   ├─ ads_top      → Above main content
   ├─ ads_sidebar  → Sidebar (if layout supports)
   ├─ ads_bottom   → Below content
   └─ ads_insights → In blog/insights pages (primary)
```

**Features**:
- ✅ IP-based rate limiting (prevent ad fatigue)
- ✅ Rotating multiple ad codes
- ✅ Campaign scheduling (start/end dates)
- ✅ Page-specific ads
- ✅ Analytics tracking (impressions, unique IPs)

---

## Filament Admin Panel

### Resources

#### ProductResource
- Multi-tab form interface
- Dynamic form based on product type
- Download source configuration (repeater)
- Product resources/variants (repeater)
- SEO & publishing options
- Table with filters & search

**Features**:
- Create products of any type
- Manage multiple download sources
- Configure licensing per resource
- Schedule publishing (draft/published/archived)
- Featured product management

#### StorageProviderResource
View-only resource (pre-seeded). Shows available providers and their requirements.

#### StorageCredentialResource
Create and manage credentials for private repositories.

**Encrypted Storage**: API tokens encrypted before saving.

#### AdvertisementResource (To Create)
Manage ad placements, campaigns, and performance.

---

## Download Flow

```
User on Nuxt → Click "Download"
    ↓
Check: is_payable?
    ├─ TRUE (Paid)
    │   └─ Redirect to Filament dashboard for purchase
    │
    └─ FALSE (Free)
        ├─ Check: requires_account?
        │   ├─ TRUE → User must login
        │   └─ FALSE → Allow guest download
        │
        ├─ Backend: Validate license type
        ├─ Generate license key (if needed)
        ├─ Resolve download source (GIT_REPO, S3, Local, etc)
        ├─ Return signed URL (15-24hr expiry)
        ├─ Log download attempt
        └─ Return to Nuxt with license info
            ├─ Display license modal
            ├─ Show attribution requirement
            └─ Trigger download
```

---

## API Endpoints (To Create)

```
POST /api/products/{slug}/initiate-download
├─ Check: free or paid?
├─ Validate user license
├─ Generate license key
└─ Return download URL + license

GET /api/products/{slug}/download-redirect
├─ Resolve storage source
├─ Generate signed URL
└─ Redirect or return link

POST /api/licenses/verify
├─ Input: license_key
└─ Validate & return details

GET /api/products/{slug}/stats
├─ Download count
├─ Unique downloads
└─ Popular resources
```

---

## Seeder Examples

### ProductSeeder (10 example products)

**Free Products**:
1. Vue 3 Dashboard Template (template, FREE_ATTRIBUTION)
2. Flappy Bird Clone Game (game, FREE_UNLIMITED)
3. PHP Validation Library (package, FREE_ATTRIBUTION)
4. Icon Pack 500+ (media, FREE_ATTRIBUTION)
5. WordPress SEO Plugin (plugin, FREE_ATTRIBUTION)

**Paid Products**:
6. Next.js SaaS Boilerplate (template, $129.99)
7. Advanced E-Commerce API (package, $199.99)
8. Premium UI Kit (media, $79.99)
9. WooCommerce Shop Manager (plugin, $149.99)
10. 2048 Premium Game (game, $4.99)

Each has ProductConfig → LOCAL_STORAGE pointing to `storage/products/{slug}/v{version}/`

### StorageProviderSeeder
7 pre-configured providers ready for credentials:
- LOCAL_STORAGE, GITHUB, BITBUCKET, GOOGLE_DRIVE, AWS_S3, DROPBOX, EXTERNAL_URL

---

## Frontend Integration (Nuxt)

### Product Display
- Update `/products/[slug]/index.vue` to show all product types
- Display license info (attribution requirements)
- Show download sources (demo, source code, binary, etc)

### Download Handling
```typescript
// pages/products/[slug]/index.vue

async function handleDownload() {
  if (product.is_payable && !hasLicense) {
    // Redirect to Filament dashboard for purchase
    navigateTo(`https://account.mintreu.com/products/${slug}/purchase`)
    return
  }

  // Free download
  const response = await $fetch(`/api/products/${slug}/initiate-download`, {
    method: 'POST'
  })

  // Start download
  window.open(response.download_url, '_blank')

  // Show license modal if needed
  if (response.license_key) {
    showLicenseModal(response)
  }
}
```

### Layout Changes
- Full-width layout option (remove sidebar constraints)
- Ad placement zones:
  - `<AdZone placement="ads_top" />` - Hero area
  - `<AdZone placement="ads_insights" />` - Blog/insights pages
  - `<AdZone placement="ads_bottom" />` - Footer area

---

## File Storage Structure

```
storage/
├─ app/
│   ├─ private/          ← Development/private files
│   │   ├─ templates/
│   │   ├─ games/
│   │   ├─ packages/
│   │   └─ media/
│   │
│   └─ public/           ← Served via signed URLs
│       ├─ products/
│       │   ├─ vue3-dashboard-template/
│       │   │   └─ v1.0/
│       │   │       └─ vue3-dashboard-template-1.0.zip
│       │   ├─ icon-pack-500/
│       │   │   └─ v2.0/
│       │   │       └─ icon-pack-500-2.0.zip
│       │   └─ flappy-bird-game/
│       │       └─ v1.2/
│       │           └─ flappy-bird-game-1.2.zip
│       │
│       └─ temp/         ← Temporary files, auto-cleanup
│           └─ signed-urls/
```

**Seeder**: Zips files from `app/private/` and stores as downloadable zip in `app/public/products/`

---

## Security Checklist

- ✅ Encrypted API tokens in StorageCredential
- ✅ Signed URLs for downloads (time-limited)
- ✅ License key validation on download
- ✅ Rate limiting per IP for ads
- ✅ CORS configuration for production domains
- ✅ Slug-based product access (no ID enumeration)
- ✅ User authentication for paid products
- ✅ Download audit trail (DownloadLog)
- ✅ File integrity (SHA256 checksums)
- ✅ Private repository credentials encrypted

---

## Next Implementation Steps

1. **Create LicenseService**
   - Generate licenses with expiry
   - Validate license usage
   - Handle commercial vs free licensing

2. **Create Download API Endpoints**
   - initiate-download
   - download-redirect
   - license-verify
   - statistics

3. **Create DownloadService**
   - Resolve storage sources
   - Generate signed URLs
   - Handle different providers

4. **Create Filament AdvertisementResource**
   - Manage ad placements
   - Campaign scheduling
   - View analytics

5. **Frontend Nuxt Updates**
   - Update product pages for all types
   - Add download components
   - Add ad display zones
   - Full-width layout option

6. **Testing**
   - Unit tests for License, Download services
   - API endpoint tests
   - E2E download flow testing
   - Ad rotation testing

---

## Configuration Files Needed

- `.env`:
  ```
  AWS_ACCESS_KEY_ID=
  AWS_SECRET_ACCESS_KEY=
  AWS_DEFAULT_REGION=us-east-1
  AWS_BUCKET=

  GOOGLE_DRIVE_SERVICE_ACCOUNT_JSON={}
  GITHUB_TOKEN=
  ```

- Storage driver: config/filesystems.php (S3, local configured)
- CORS: backend/config/cors.php (already set for production)

---

**Status**: Database, Models, Filament Resources, Seeders ✅ Complete
**Next**: Services, API Endpoints, Frontend integration
