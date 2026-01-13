# API Usage Tracking & Rate Limiting Plan

## Overview
A comprehensive system to track, limit, and monetize API usage for API-based products on the Mintreu marketplace. This system ensures fair usage, prevents abuse, and provides developers with detailed analytics.

## Core Components

### 1. Usage Metrics Tracking

#### Metrics Types
- **Request Count**: Total API calls per period
- **Bandwidth Usage**: Data transfer in/out
- **Error Rates**: Failed request percentages
- **Response Times**: Average API response times
- **Endpoint Usage**: Calls per specific endpoint
- **Geographic Usage**: Requests by country/region
- **Time-based Patterns**: Usage by hour/day/week

#### Database Schema
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
    period_start TIMESTAMP NOT NULL,
    period_end TIMESTAMP NOT NULL,
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
    top_endpoints JSON NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE KEY unique_license_period (license_id, period_type, period_start),
    FOREIGN KEY (license_id) REFERENCES licenses(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);
```

### 2. Rate Limiting System

#### Rate Limit Types
- **Requests per Minute**: RPM limits
- **Requests per Hour**: RPH limits
- **Requests per Day**: RPD limits
- **Concurrent Requests**: Simultaneous connections
- **Bandwidth per Period**: Data transfer limits
- **Error Rate Limits**: Prevent abuse via errors

#### Implementation
```php
class RateLimiter
{
    protected $redis;

    public function __construct()
    {
        $this->redis = Redis::connection('rate_limiting');
    }

    public function checkLimit(License $license, string $endpoint, string $ip): array
    {
        $limits = $license->usage_limits ?? [];
        $key = "rate_limit:{$license->id}:{$ip}";

        $current = [
            'minute' => $this->redis->get("{$key}:minute") ?? 0,
            'hour' => $this->redis->get("{$key}:hour") ?? 0,
            'day' => $this->redis->get("{$key}:day") ?? 0,
        ];

        $exceeded = [];

        if (isset($limits['rpm']) && $current['minute'] >= $limits['rpm']) {
            $exceeded[] = 'rpm';
        }
        if (isset($limits['rph']) && $current['hour'] >= $limits['rph']) {
            $exceeded[] = 'rph';
        }
        if (isset($limits['rpd']) && $current['day'] >= $limits['rpd']) {
            $exceeded[] = 'rpd';
        }

        return [
            'allowed' => empty($exceeded),
            'exceeded_limits' => $exceeded,
            'current_usage' => $current,
            'reset_times' => [
                'minute' => $this->getResetTime('minute'),
                'hour' => $this->getResetTime('hour'),
                'day' => $this->getResetTime('day'),
            ]
        ];
    }

    public function recordUsage(License $license, string $endpoint, string $ip): void
    {
        $key = "rate_limit:{$license->id}:{$ip}";
        $now = now();

        // Increment counters with expiration
        $this->redis->pipeline()
            ->incr("{$key}:minute")
            ->expire("{$key}:minute", 60)
            ->incr("{$key}:hour")
            ->expire("{$key}:hour", 3600)
            ->incr("{$key}:day")
            ->expire("{$key}:day", 86400)
            ->execute();
    }

    private function getResetTime(string $period): int
    {
        $now = now();
        switch ($period) {
            case 'minute':
                return $now->copy()->addMinute()->startOfMinute()->timestamp;
            case 'hour':
                return $now->copy()->addHour()->startOfHour()->timestamp;
            case 'day':
                return $now->copy()->addDay()->startOfDay()->timestamp;
        }
    }
}
```

### 3. API Gateway Middleware

#### Laravel Middleware Implementation
```php
class ApiUsageTrackerMiddleware
{
    protected $rateLimiter;
    protected $usageTracker;

    public function __construct(RateLimiter $rateLimiter, ApiUsageTracker $usageTracker)
    {
        $this->rateLimiter = $rateLimiter;
        $this->usageTracker = $usageTracker;
    }

    public function handle(Request $request, Closure $next, ...$guards)
    {
        // Extract license key from header or query parameter
        $licenseKey = $request->header('X-API-Key') ?? $request->query('api_key');

        if (!$licenseKey) {
            return response()->json(['error' => 'API key required'], 401);
        }

        // Find license
        $license = License::where('license_key', $licenseKey)
                         ->where('status', 'active')
                         ->first();

        if (!$license) {
            return response()->json(['error' => 'Invalid API key'], 401);
        }

        // Check rate limits
        $rateLimitCheck = $this->rateLimiter->checkLimit(
            $license,
            $request->path(),
            $request->ip()
        );

        if (!$rateLimitCheck['allowed']) {
            return response()->json([
                'error' => 'Rate limit exceeded',
                'limits_exceeded' => $rateLimitCheck['exceeded_limits'],
                'reset_times' => $rateLimitCheck['reset_times']
            ], 429, [
                'X-RateLimit-Reset' => max($rateLimitCheck['reset_times']),
                'Retry-After' => max($rateLimitCheck['reset_times']) - time()
            ]);
        }

        // Track request start
        $startTime = microtime(true);
        $requestSize = strlen($request->getContent());

        try {
            $response = $next($request);

            // Track successful request
            $endTime = microtime(true);
            $responseTime = (int)(($endTime - $startTime) * 1000);
            $responseSize = strlen($response->getContent());

            $this->usageTracker->trackRequest($license, $request, $response, [
                'request_size' => $requestSize,
                'response_size' => $responseSize,
                'response_time' => $responseTime
            ]);

            // Record usage for rate limiting
            $this->rateLimiter->recordUsage($license, $request->path(), $request->ip());

            // Add usage headers to response
            $response->headers->set('X-API-Usage-Current', json_encode($rateLimitCheck['current_usage']));
            $response->headers->set('X-API-Usage-Limits', json_encode($license->usage_limits ?? []));

            return $response;

        } catch (\Exception $e) {
            // Track failed request
            $this->usageTracker->trackError($license, $request, $e);
            throw $e;
        }
    }
}
```

### 4. Usage Analytics & Reporting

#### Real-time Dashboard
```php
class UsageAnalyticsService
{
    public function getUsageStats(License $license, string $period = '30d'): array
    {
        $startDate = now()->subDays($this->parsePeriod($period));

        $stats = ApiUsage::where('license_id', $license->id)
                        ->where('created_at', '>=', $startDate)
                        ->selectRaw('
                            COUNT(*) as total_requests,
                            AVG(response_time) as avg_response_time,
                            SUM(request_size + response_size) as total_bandwidth,
                            COUNT(CASE WHEN status_code >= 400 THEN 1 END) as error_count
                        ')
                        ->first();

        $topEndpoints = ApiUsage::where('license_id', $license->id)
                               ->where('created_at', '>=', $startDate)
                               ->groupBy('endpoint')
                               ->selectRaw('endpoint, COUNT(*) as count')
                               ->orderBy('count', 'desc')
                               ->limit(10)
                               ->get();

        return [
            'total_requests' => $stats->total_requests ?? 0,
            'avg_response_time' => round($stats->avg_response_time ?? 0, 2),
            'total_bandwidth' => $stats->total_bandwidth ?? 0,
            'error_rate' => $stats->total_requests > 0
                ? round(($stats->error_count / $stats->total_requests) * 100, 2)
                : 0,
            'top_endpoints' => $topEndpoints,
            'usage_trend' => $this->getUsageTrend($license, $startDate)
        ];
    }

    public function getUsageTrend(License $license, Carbon $startDate): array
    {
        return UsageSummary::where('license_id', $license->id)
                          ->where('period_type', 'daily')
                          ->where('period_start', '>=', $startDate)
                          ->orderBy('period_start')
                          ->pluck('total_requests', 'period_start')
                          ->toArray();
    }
}
```

#### Scheduled Reports
```php
class GenerateUsageReports implements ShouldQueue
{
    public function handle()
    {
        $yesterday = now()->subDay();

        // Generate daily summaries
        $usages = ApiUsage::whereDate('created_at', $yesterday)
                          ->selectRaw('
                              license_id,
                              product_id,
                              COUNT(*) as total_requests,
                              SUM(request_size + response_size) as total_bandwidth,
                              AVG(response_time) as avg_response_time,
                              COUNT(CASE WHEN status_code >= 400 THEN 1 END) as error_count,
                              COUNT(DISTINCT ip_address) as unique_ips
                          ')
                          ->groupBy('license_id', 'product_id')
                          ->get();

        foreach ($usages as $usage) {
            UsageSummary::updateOrCreate(
                [
                    'license_id' => $usage->license_id,
                    'period_type' => 'daily',
                    'period_start' => $yesterday->startOfDay(),
                    'period_end' => $yesterday->endOfDay(),
                ],
                [
                    'product_id' => $usage->product_id,
                    'total_requests' => $usage->total_requests,
                    'total_bandwidth' => $usage->total_bandwidth,
                    'avg_response_time' => $usage->avg_response_time,
                    'error_count' => $usage->error_count,
                    'unique_ips' => $usage->unique_ips,
                ]
            );
        }

        // Send reports to developers
        $this->sendDeveloperReports($yesterday);
    }

    private function sendDeveloperReports(Carbon $date)
    {
        $developers = User::whereHas('products', function($query) {
            $query->where('type', 'api');
        })->get();

        foreach ($developers as $developer) {
            $reportData = $this->generateDeveloperReport($developer, $date);
            Notification::send($developer, new DailyUsageReport($reportData));
        }
    }
}
```

### 5. Billing Integration

#### Usage-Based Billing
```php
class UsageBasedBillingService
{
    public function calculateBill(License $license, Carbon $startDate, Carbon $endDate): array
    {
        $usage = UsageSummary::where('license_id', $license->id)
                            ->where('period_start', '>=', $startDate)
                            ->where('period_end', '<=', $endDate)
                            ->sum('total_requests');

        $pricing = $license->product->pricing;

        $billableRequests = max(0, $usage - ($pricing['free_tier'] ?? 0));
        $costPerRequest = $pricing['cost_per_request'] ?? 0.001; // $0.001 per request

        $totalCost = $billableRequests * $costPerRequest;

        return [
            'total_requests' => $usage,
            'billable_requests' => $billableRequests,
            'cost_per_request' => $costPerRequest,
            'total_cost' => $totalCost,
            'free_tier_used' => min($usage, $pricing['free_tier'] ?? 0)
        ];
    }

    public function processUsageBilling()
    {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        $licenses = License::where('billing_type', 'usage-based')
                          ->where('status', 'active')
                          ->get();

        foreach ($licenses as $license) {
            $billing = $this->calculateBill($license, $startOfMonth, $endOfMonth);

            if ($billing['total_cost'] > 0) {
                // Create invoice
                $invoice = Invoice::create([
                    'license_id' => $license->id,
                    'user_id' => $license->user_id,
                    'amount' => $billing['total_cost'],
                    'billing_period_start' => $startOfMonth,
                    'billing_period_end' => $endOfMonth,
                    'status' => 'pending'
                ]);

                // Process payment
                $this->processPayment($invoice);
            }
        }
    }
}
```

### 6. Developer API

#### Usage Analytics API
```php
// Routes
Route::middleware(['auth:sanctum', 'developer'])->group(function () {
    Route::get('/api/usage/stats/{license}', [ApiUsageController::class, 'stats']);
    Route::get('/api/usage/trend/{license}', [ApiUsageController::class, 'trend']);
    Route::get('/api/usage/logs/{license}', [ApiUsageController::class, 'logs']);
    Route::post('/api/limits/{license}', [ApiUsageController::class, 'updateLimits']);
});
```

### 7. Client SDK

#### JavaScript SDK for API Consumers
```javascript
class MintreuApiClient {
    constructor(apiKey, baseUrl = 'https://api.mintreu.com') {
        this.apiKey = apiKey;
        this.baseUrl = baseUrl;
        this.usage = { requests: 0, bandwidth: 0 };
    }

    async request(endpoint, options = {}) {
        const url = `${this.baseUrl}${endpoint}`;

        const response = await fetch(url, {
            ...options,
            headers: {
                'X-API-Key': this.apiKey,
                'Content-Type': 'application/json',
                ...options.headers
            }
        });

        // Track usage
        this.usage.requests++;
        if (response.headers.get('content-length')) {
            this.usage.bandwidth += parseInt(response.headers.get('content-length'));
        }

        // Check rate limit headers
        const rateLimitReset = response.headers.get('X-RateLimit-Reset');
        if (rateLimitReset) {
            console.warn(`Rate limit reset at: ${new Date(rateLimitReset * 1000)}`);
        }

        if (response.status === 429) {
            const retryAfter = response.headers.get('Retry-After');
            throw new Error(`Rate limit exceeded. Retry after ${retryAfter} seconds.`);
        }

        return response;
    }

    getUsageStats() {
        return { ...this.usage };
    }
}
```

### 8. Monitoring & Alerts

#### Automated Alerts
- **Usage Threshold Alerts**: 80%, 90%, 100% of limits
- **Error Rate Spikes**: Sudden increase in error rates
- **Performance Degradation**: Response time increases
- **Suspicious Activity**: Unusual usage patterns

#### Real-time Monitoring Dashboard
- Live usage graphs
- Error rate monitoring
- Geographic usage maps
- Performance metrics

This comprehensive API usage tracking system ensures fair usage, provides detailed analytics, and enables flexible monetization strategies for API-based products on the Mintreu marketplace.