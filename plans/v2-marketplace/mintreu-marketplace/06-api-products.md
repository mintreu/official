# Chapter 6: API and Usage-Based Products

This chapter details the infrastructure and processes for supporting API-based products and other usage-based offerings within the Mintreu Marketplace. This includes API gateway functionality, comprehensive usage tracking, robust rate limiting, and integration with billing systems.

## 6.1. The API Gateway: A central point for all API products.

All API products sold through Mintreu will be exposed via a unified API gateway managed by the platform. This gateway will handle authentication, authorization, license validation, usage tracking, and rate limiting before requests are proxied to the actual developer-provided API endpoints.

*   **Unified Endpoint**: All API product requests will go through `api.mintreu.com/v1/{developer_slug}/{product_slug}/{endpoint}`.
*   **Authentication**: API keys (`X-API-Key` header or `api_key` query parameter) will be used to identify the customer's license.
*   **License Validation**: The API gateway will perform real-time license validation (as detailed in Chapter 5) for every incoming request.
*   **Request Routing**: Based on the validated license and product, requests will be securely routed to the developer's actual API endpoint. This might involve internal routing or secure external calls.
*   **Response Handling**: The gateway will capture response metrics (status code, size, time) and potentially inject usage headers.

## 6.2. Usage Tracking: Metering API calls, bandwidth, etc.

Accurate and real-time tracking of API usage is fundamental for usage-based billing and fair-use policies.

**Metrics Types**:

*   **Request Count**: Total API calls made within a specific period.
*   **Bandwidth Usage**: Data transferred (request and response size) in bytes.
*   **Error Rates**: Percentage of failed requests (e.g., 4xx, 5xx status codes).
*   **Response Times**: Average latency of API responses.
*   **Endpoint Usage**: Granular tracking of calls to specific API endpoints.
*   **Geographic Usage**: Origin of API requests (IP address, country code).
*   **Time-based Patterns**: Usage distribution over hours, days, weeks, or months.

**Database Schema (from `api-usage-tracking-plan.md`):**

```sql
CREATE TABLE api_usages (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    license_id BIGINT NOT NULL,
    product_id BIGINT NOT NULL,
    endpoint VARCHAR(255) NOT NULL,
    method ENUM('GET', 'POST', 'PUT', 'DELETE', 'PATCH') NOT NULL,
    status_code INT NOT NULL,
    request_size INT DEFAULT 0, -- bytes
    response_size INT DEFAULT 0, -- bytes
    response_time INT DEFAULT 0, -- milliseconds
    ip_address VARCHAR(45),
    user_agent TEXT,
    country_code CHAR(2),
    period_start TIMESTAMP NOT NULL, -- Start of the tracking period (e.g., minute, hour)
    period_end TIMESTAMP NOT NULL,   -- End of the tracking period
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_license_period (license_id, period_start, period_end),
    INDEX idx_product_endpoint (product_id, endpoint),
    FOREIGN KEY (license_id) REFERENCES licenses(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE usage_summaries (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    license_id BIGINT NOT NULL,
    product_id BIGINT NOT NULL,
    period_type ENUM('hourly', 'daily', 'weekly', 'monthly') NOT NULL,
    period_start TIMESTAMP NOT NULL,
    period_end TIMESTAMP NOT NULL,
    total_requests INT DEFAULT 0,
    total_bandwidth BIGINT DEFAULT 0, -- bytes
    error_count INT DEFAULT 0,
    avg_response_time INT DEFAULT 0,
    unique_ips INT DEFAULT 0,
    top_endpoints JSON NULL, -- Store aggregated endpoint usage as JSON
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE KEY unique_license_period (license_id, period_type, period_start),
    FOREIGN KEY (license_id) REFERENCES licenses(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);
```

**Implementation**:

*   **Middleware**: A dedicated Laravel middleware (`ApiUsageTrackerMiddleware`) will intercept all API requests for licensed products.
*   **Real-time Recording**: Each request will be recorded in the `api_usages` table.
*   **Aggregated Summaries**: A scheduled job will process raw `api_usages` data into `usage_summaries` for various periods (hourly, daily, monthly). This reduces the load on the `api_usages` table and speeds up reporting.

## 6.3. Rate Limiting: Preventing abuse and ensuring fair usage.

Rate limiting is essential to protect the platform and developer APIs from abuse, ensure fair resource allocation, and enforce usage-based license terms.

**Rate Limit Types**:

*   **Requests per Minute (RPM)**
*   **Requests per Hour (RPH)**
*   **Requests per Day (RPD)**
*   **Concurrent Requests**: Limit simultaneous open connections.
*   **Bandwidth per Period**: Data transfer limits.
*   **Error Rate Limits**: Automatically throttle clients with high error rates.

**Implementation (from `api-usage-tracking-plan.md`):**

*   **Redis-backed Rate Limiter**: Utilize Redis for fast, atomic incrementing and expiration of counters.
*   **`RateLimiter` Service**: A dedicated service to manage rate limit checks and usage recording.

```php
// Simplified example from api-usage-tracking-plan.md
class RateLimiter
{
    protected $redis;

    public function __construct()
    {
        $this->redis = Redis::connection('rate_limiting'); // Dedicated Redis connection
    }

    public function checkLimit(License $license, string $endpoint, string $ip): array
    {
        // ... (logic to check limits based on license->usage_limits and current Redis counters)
    }

    public function recordUsage(License $license, string $endpoint, string $ip): void
    {
        // ... (logic to increment Redis counters with appropriate expirations)
    }
}
```

*   **Integration with Middleware**: The `ApiUsageTrackerMiddleware` will call the `RateLimiter` service before processing the request. If limits are exceeded, a `429 Too Many Requests` response will be returned with `X-RateLimit-Reset` and `Retry-After` headers.
*   **Configurable Limits**: Developers will define default rate limits for their products/packages, which can be overridden for individual licenses by Super Admins.

## 6.4. Billing Integration: Pay-as-you-go billing models.

For API and other usage-based products, the system will integrate with billing to charge customers based on their actual consumption.

*   **Flexible Pricing**: Support for tiered pricing, volume pricing, and flat fees per unit of usage.
*   **Usage Aggregation**: Monthly (or other billing period) aggregation of `usage_summaries` data for each license.
*   **Invoice Generation**: Automated generation of invoices based on aggregated usage and product pricing.
*   **Payment Processing**: Integration with Stripe/PayPal for automated charging of usage-based fees.
*   **Developer Payouts**: Calculation of developer revenue share based on usage-based sales.
*   **Customer Visibility**: Customers will have a clear view of their current usage and estimated costs in their dashboard.
*   **Alerts**: Notifications for customers when their usage approaches certain billing thresholds.

*(Refer to `api-usage-tracking-plan.md` for the detailed `UsageBasedBillingService` example.)*
