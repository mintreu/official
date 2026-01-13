# Licensing System Plan

## Overview
A comprehensive licensing system that handles different product types, enforces usage limits, prevents unauthorized access, and provides developers with full control over their digital products.

## License Types

### 1. Product-Based Licenses
- **Regular License**: Single domain/website usage
- **Extended License**: Multiple domains/unlimited usage
- **Developer License**: For development and testing

### 2. Time-Based Licenses
- **Lifetime License**: No expiration
- **Annual License**: 1-year validity with auto-renewal options
- **Monthly License**: Recurring monthly billing
- **Trial License**: Limited time free access

### 3. Usage-Based Licenses
- **API Call Limits**: X calls per minute/hour/day/month
- **Bandwidth Limits**: Data transfer limits
- **Storage Limits**: File storage quotas
- **User Limits**: Concurrent user restrictions

## License Key Structure

### Key Format
```
MINTREU-{PRODUCT_TYPE}-{DEVELOPER_ID}-{TIMESTAMP}-{RANDOM_STRING}
Example: MINTREU-API-001-20241110-ABC123XYZ
```

### Key Components
- **Prefix**: MINTREU (platform identifier)
- **Product Type**: API, SCRIPT, PLUGIN, etc.
- **Developer ID**: Unique developer identifier
- **Timestamp**: Creation date (YYYYMMDD)
- **Random String**: 9-character alphanumeric (avoid ambiguous chars)

## Database Schema

### licenses table
```sql
CREATE TABLE licenses (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    license_key VARCHAR(255) UNIQUE NOT NULL,
    product_id BIGINT NOT NULL,
    user_id BIGINT NOT NULL,
    developer_id BIGINT NOT NULL,
    license_type ENUM('regular', 'extended', 'developer') DEFAULT 'regular',
    billing_type ENUM('one-time', 'monthly', 'annual', 'lifetime') DEFAULT 'one-time',
    status ENUM('active', 'expired', 'suspended', 'cancelled') DEFAULT 'active',
    expires_at TIMESTAMP NULL,
    activated_at TIMESTAMP NULL,
    last_used_at TIMESTAMP NULL,
    usage_limits JSON NULL, -- {"api_calls": 1000, "bandwidth": "10GB", etc.}
    current_usage JSON NULL, -- {"api_calls": 450, "bandwidth": "2.5GB", etc.}
    domain_restrictions JSON NULL, -- ["example.com", "*.example.com"]
    ip_restrictions JSON NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (developer_id) REFERENCES users(id)
);
```

### license_logs table
```sql
CREATE TABLE license_logs (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    license_id BIGINT NOT NULL,
    action ENUM('created', 'activated', 'validated', 'expired', 'suspended', 'usage_exceeded') NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    domain VARCHAR(255),
    metadata JSON NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (license_id) REFERENCES licenses(id)
);
```

## License Validation Flow

### 1. Client-Side Validation
```javascript
// Client library for license validation
class LicenseValidator {
    constructor(licenseKey, productId) {
        this.licenseKey = licenseKey;
        this.productId = productId;
        this.apiUrl = 'https://api.mintreu.com/v1/license/validate';
    }

    async validate() {
        try {
            const response = await fetch(this.apiUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-API-Key': this.licenseKey
                },
                body: JSON.stringify({
                    product_id: this.productId,
                    domain: window.location.hostname,
                    ip: await this.getClientIP()
                })
            });

            const result = await response.json();
            return {
                valid: result.valid,
                expires_at: result.expires_at,
                usage_limits: result.usage_limits,
                current_usage: result.current_usage
            };
        } catch (error) {
            return { valid: false, error: error.message };
        }
    }
}
```

### 2. Server-Side Validation API
```php
// Laravel Controller
class LicenseController extends Controller
{
    public function validate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'license_key' => 'required|string',
            'product_id' => 'required|integer',
            'domain' => 'required|string',
            'ip' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['valid' => false, 'error' => 'Invalid request']);
        }

        $license = License::where('license_key', $request->license_key)
                          ->where('product_id', $request->product_id)
                          ->first();

        if (!$license) {
            return response()->json(['valid' => false, 'error' => 'License not found']);
        }

        // Check expiration
        if ($license->expires_at && $license->expires_at->isPast()) {
            $license->update(['status' => 'expired']);
            return response()->json(['valid' => false, 'error' => 'License expired']);
        }

        // Check domain restrictions
        if ($license->domain_restrictions) {
            $allowedDomains = $license->domain_restrictions;
            $currentDomain = $request->domain;

            if (!$this->isDomainAllowed($currentDomain, $allowedDomains)) {
                return response()->json(['valid' => false, 'error' => 'Domain not authorized']);
            }
        }

        // Check usage limits
        if ($license->usage_limits) {
            $currentUsage = $license->current_usage ?? [];
            $limits = $license->usage_limits;

            if ($this->hasExceededLimits($currentUsage, $limits)) {
                return response()->json(['valid' => false, 'error' => 'Usage limit exceeded']);
            }
        }

        // Log validation attempt
        LicenseLog::create([
            'license_id' => $license->id,
            'action' => 'validated',
            'ip_address' => $request->ip,
            'domain' => $request->domain,
            'metadata' => ['user_agent' => $request->userAgent()]
        ]);

        return response()->json([
            'valid' => true,
            'expires_at' => $license->expires_at,
            'usage_limits' => $license->usage_limits,
            'current_usage' => $license->current_usage
        ]);
    }
}
```

## Usage Tracking System

### API Usage Tracking
```php
class ApiUsageTracker
{
    public function trackUsage(License $license, string $endpoint, array $metadata = [])
    {
        // Increment usage counters
        $currentUsage = $license->current_usage ?? [];
        $currentUsage['api_calls'] = ($currentUsage['api_calls'] ?? 0) + 1;
        $currentUsage['last_call_at'] = now();

        // Track endpoint-specific usage
        $endpointKey = 'endpoint_' . md5($endpoint);
        $currentUsage[$endpointKey] = ($currentUsage[$endpointKey] ?? 0) + 1;

        $license->update(['current_usage' => $currentUsage]);

        // Log the usage
        LicenseLog::create([
            'license_id' => $license->id,
            'action' => 'usage_tracked',
            'metadata' => array_merge($metadata, [
                'endpoint' => $endpoint,
                'usage_type' => 'api_call'
            ])
        ]);

        // Check if limits exceeded
        if ($license->usage_limits && $this->hasExceededLimits($currentUsage, $license->usage_limits)) {
            $this->handleLimitExceeded($license);
        }
    }
}
```

### Scheduled Tasks
```php
// Check for expired licenses daily
class CheckExpiredLicenses implements ShouldQueue
{
    public function handle()
    {
        License::where('expires_at', '<', now())
               ->where('status', 'active')
               ->update(['status' => 'expired']);

        // Notify developers and customers
        $expiredLicenses = License::where('status', 'expired')
                                 ->where('updated_at', '>', now()->subDay())
                                 ->get();

        foreach ($expiredLicenses as $license) {
            // Send notifications
            Notification::send($license->user, new LicenseExpired($license));
            Notification::send($license->developer, new LicenseExpiredForDeveloper($license));
        }
    }
}
```

## Security Measures

### Anti-Forgery Protection
1. **License Key Obfuscation**: Never expose full keys in client-side code
2. **Domain Locking**: Restrict licenses to specific domains
3. **IP Whitelisting**: Optional IP-based restrictions
4. **Rate Limiting**: Prevent brute force validation attempts
5. **Checksum Validation**: Include checksums in license keys for tampering detection

### License Key Generation
```php
class LicenseKeyGenerator
{
    public function generate(Product $product, User $developer): string
    {
        $timestamp = now()->format('Ymd');
        $random = $this->generateSecureRandomString(9);
        $developerId = str_pad($developer->id, 3, '0', STR_PAD_LEFT);

        $key = "MINTREU-{$product->type}-{$developerId}-{$timestamp}-{$random}";

        // Add checksum for validation
        $checksum = $this->generateChecksum($key);
        return $key . '-' . $checksum;
    }

    private function generateSecureRandomString(int $length): string
    {
        $characters = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789'; // Avoid ambiguous chars
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $result .= $characters[random_int(0, strlen($characters) - 1)];
        }
        return $result;
    }

    private function generateChecksum(string $key): string
    {
        return strtoupper(substr(md5($key . env('LICENSE_SECRET')), 0, 4));
    }
}
```

## Developer Dashboard Features

### License Management
- View all licenses for their products
- Manually suspend/revoke licenses
- Generate replacement keys
- View usage analytics
- Set custom usage limits per license

### Customer Management
- View customers who purchased their products
- Send license keys manually
- Handle support requests
- View customer usage patterns

## Customer Experience

### License Portal
- View all owned licenses
- Download license keys
- View expiration dates
- Monitor usage statistics
- Request license extensions
- Transfer licenses (with developer approval)

### Automated Notifications
- License expiration warnings (30, 7, 1 day before)
- Usage limit warnings (80%, 90%, 100%)
- Renewal reminders
- Security alerts

This licensing system provides developers with complete control over their digital products while ensuring customers have a smooth experience managing their purchases.