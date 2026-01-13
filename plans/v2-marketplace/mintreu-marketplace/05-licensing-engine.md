# Chapter 5: The Licensing Engine

The licensing engine is a critical component of the Mintreu Marketplace, ensuring that products are used according to their terms, preventing unauthorized distribution, and enabling flexible monetization models. This chapter details the types of licenses, key generation, validation, forgery prevention, and lifecycle management.

## 5.1. License Types: A deep dive into different license models.

The platform will support a variety of license types to accommodate diverse product offerings. These types will be configurable by developers when listing their products and defining packages.

*   **Product-Based Licenses**:
    *   **Regular License**: Typically for single domain/website usage. Common for themes, plugins, or scripts.
    *   **Extended License**: Allows usage on multiple domains, unlimited projects, or for client work.
    *   **Developer License**: Grants broader usage rights, often including access to source code, development environments, and priority support.
*   **Time-Based Licenses**:
    *   **Lifetime License**: Grants perpetual usage rights, with ongoing updates and support for a defined period (e.g., 1 year of updates, then optional renewal for updates).
    *   **Annual License**: Valid for one year, typically with auto-renewal options.
    *   **Monthly License**: Recurring monthly billing, common for SaaS or ongoing service access.
    *   **Trial License**: Limited-time free access to a product, often with feature restrictions, to allow customers to evaluate before purchase.
*   **Usage-Based Licenses**:
    *   **API Call Limits**: Defines the maximum number of API requests per minute, hour, day, or month.
    *   **Bandwidth Limits**: Sets data transfer quotas (e.g., GB per month).
    *   **Storage Limits**: Specifies file storage capacity (e.g., MB/GB).
    *   **User Limits**: Restricts the number of concurrent users or active accounts.

## 5.2. License Key Generation: Secure and verifiable keys.

License keys must be unique, secure, and contain enough information to be validated without necessarily querying the central server for every check (though server-side validation will always be the ultimate authority).

**Key Format**:
`MINTREU-{PRODUCT_TYPE}-{DEVELOPER_ID}-{TIMESTAMP}-{RANDOM_STRING}-{CHECKSUM}`

**Key Components**:

*   **Prefix (`MINTREU`)**: A static identifier for the platform.
*   **Product Type (`API`, `SCRIPT`, `PLUGIN`, `SAAS`, `THEME`, `CONSULT`)**: Categorizes the product, aiding in initial validation logic.
*   **Developer ID**: A unique identifier for the product's developer. This can be an obfuscated or hashed version of the actual developer ID.
*   **Timestamp**: The date of license generation (YYYYMMDD), useful for tracking and initial expiration checks.
*   **Random String**: A cryptographically secure, alphanumeric string (e.g., 9 characters, excluding ambiguous characters like `0`, `O`, `1`, `I`, `l`) to ensure uniqueness and prevent brute-force guessing.
*   **Checksum**: A short hash of the preceding components, signed with a private key (or a secret environment variable), to detect tampering.

**Generation Process**:

```php
class LicenseKeyGenerator
{
    /**
     * Generates a unique and verifiable license key.
     *
     * @param Product $product The product for which the license is being generated.
     * @param User $developer The developer of the product.
     * @return string The generated license key.
     */
    public function generate(Product $product, User $developer): string
    {
        $timestamp = now()->format('Ymd');
        $random = $this->generateSecureRandomString(9);
        // Use a hashed or UUID version of developer ID for external keys
        $developerIdentifier = substr(md5($developer->id . env('APP_KEY')), 0, 8);

        $baseKey = "MINTREU-{$product->type}-{$developerIdentifier}-{$timestamp}-{$random}";

        // Add checksum for validation
        $checksum = $this->generateChecksum($baseKey);
        return $baseKey . '-' . $checksum;
    }

    /**
     * Generates a cryptographically secure random string.
     *
     * @param int $length The desired length of the string.
     * @return string
     */
    private function generateSecureRandomString(int $length): string
    {
        $characters = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789'; // Avoid ambiguous chars
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $result .= $characters[random_int(0, strlen($characters) - 1)];
        }
        return $result;
    }

    /**
     * Generates a checksum for the license key to detect tampering.
     *
     * @param string $key The base license key string.
     * @return string The generated checksum.
     */
    private function generateChecksum(string $key): string
    {
        // Use a strong hashing algorithm and a secret key
        return strtoupper(substr(hash_hmac('sha256', $key, env('LICENSE_SECRET')), 0, 8));
    }

    /**
     * Validates the checksum of a given license key.
     *
     * @param string $fullKey The full license key including the checksum.
     * @return bool True if the checksum is valid, false otherwise.
     */
    public function validateChecksum(string $fullKey): bool
    {
        $parts = explode('-', $fullKey);
        if (count($parts) < 6) { // MINTREU-TYPE-DEV-TIMESTAMP-RANDOM-CHECKSUM
            return false;
        }
        $providedChecksum = array_pop($parts);
        $baseKey = implode('-', $parts);
        $expectedChecksum = $this->generateChecksum($baseKey);

        return hash_equals($providedChecksum, $expectedChecksum);
    }
}
```

## 5.3. License Validation: Client-side and server-side validation.

Validation will occur at multiple layers to ensure robust license enforcement.

*   **Client-Side Validation (SDK/Helper Library)**:
    *   A lightweight SDK (e.g., PHP package for Laravel projects, JavaScript library for frontend apps) will be provided to customers.
    *   This SDK will perform initial checks:
        *   **Format Validation**: Check if the key adheres to the `MINTREU-...` format.
        *   **Checksum Validation**: Verify the integrity of the key using the embedded checksum.
        *   **Basic Expiration Check**: If the key contains an expiration date, a preliminary check can be done locally.
    *   The SDK will then make an API call to the Mintreu server for definitive validation.
    *   **Example (PHP SDK)**:
        ```php
        // In customer's Laravel project
        use Mintreu\LicenseClient\LicenseValidator;

        $validator = new LicenseValidator(env('MINTREU_LICENSE_KEY'), env('MINTREU_PRODUCT_ID'));
        $validationResult = $validator->validate();

        if ($validationResult->isValid()) {
            // Proceed with product functionality
        } else {
            // Handle invalid license (e.g., disable features, show warning)
        }
        ```

*   **Server-Side Validation (Mintreu API)**:
    *   This is the authoritative source for license status.
    *   The validation API endpoint (`/api/v1/license/validate`) will receive the license key, product ID, and potentially client metadata (domain, IP address, user agent).
    *   **Validation Steps**:
        1.  **Checksum Verification**: Re-calculate and compare the checksum.
        2.  **Database Lookup**: Retrieve the full license record from the `licenses` table.
        3.  **Status Check**: Verify `status` (active, expired, suspended).
        4.  **Expiration Check**: Compare `expires_at` with current time.
        5.  **Domain Restrictions**: If `domain_restrictions` are set, verify the incoming request's domain against the allowed list. Wildcard domains (`*.example.com`) should be supported.
        6.  **IP Restrictions**: If `ip_restrictions` are set, verify the incoming request's IP address.
        7.  **Usage Limit Check**: For usage-based licenses, check `current_usage` against `usage_limits`.
        8.  **Log Validation**: Record the validation attempt in `license_logs` for auditing and debugging.
    *   **Response**: Return a clear JSON response indicating validity, expiration details, current usage, and remaining limits.

## 5.4. Forgery Prevention: Techniques to prevent license tampering.

Preventing license key forgery and unauthorized usage is paramount.

*   **Checksums**: As described above, a cryptographic checksum prevents simple modification of the key components. The `LICENSE_SECRET` environment variable must be kept secure on the Mintreu server.
*   **Server-Side Enforcement**: All critical validation logic resides on the Mintreu server. Client-side checks are for convenience and initial feedback, but cannot be trusted for ultimate enforcement.
*   **Domain/IP Locking**: Licenses can be tied to specific domains or IP addresses, making it harder to use a single key across multiple unauthorized installations.
*   **Rate Limiting on Validation API**: Prevent brute-force attacks on the validation endpoint. (See Chapter 6: API and Usage-Based Products).
*   **Frequent Re-validation**: Client SDKs should periodically re-validate licenses with the Mintreu server, not just once at installation. The frequency can be configurable.
*   **Obfuscation**: While not foolproof, obfuscating client-side SDK code can deter casual reverse-engineering attempts.
*   **Revocation**: Developers (and Super Admins) can instantly revoke a license from their dashboard, rendering it invalid on the next server-side validation.
*   **Usage Tracking**: Monitoring usage patterns can help identify suspicious activity (e.g., a single license key making an unusually high number of validation requests from different IPs/domains).

## 5.5. Expiration and Renewal: Automated management of license lifecycle.

The system will automate the management of license expiration and renewal processes.

*   **`expires_at` Field**: The `licenses` table will have an `expires_at` timestamp.
*   **Scheduled Tasks (Laravel Scheduler)**:
    *   **Daily Check for Expired Licenses**: A scheduled job (`CheckExpiredLicenses`) will run daily to identify licenses where `expires_at` is in the past and `status` is 'active'. These licenses will be updated to 'expired'.
    *   **Renewal Reminders**: Automated email notifications sent to customers 30, 7, and 1 day(s) before a license expires, prompting them to renew.
    *   **Usage Limit Warnings**: For usage-based licenses, notifications sent when usage approaches (e.g., 80%, 90%) or exceeds limits.
*   **Renewal Process**:
    *   Customers can renew licenses directly from their dashboard.
    *   For subscription-based licenses, auto-renewal will be attempted using the stored payment method.
    *   Upon successful renewal, `expires_at` will be updated, and the license `status` will remain 'active'.
*   **Suspension/Cancellation**:
    *   Licenses can be manually suspended or cancelled by developers or Super Admins (e.g., due to payment issues, terms violation).
    *   Automated suspension for failed recurring payments.
    *   Suspended/cancelled licenses will fail server-side validation.

*(Refer to `licensing-system-plan.md` for the detailed `licenses` and `license_logs` table schemas, which will be directly used for implementation.)*
