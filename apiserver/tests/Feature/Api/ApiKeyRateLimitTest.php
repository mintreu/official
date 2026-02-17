<?php

use App\Enums\LicenseType;
use App\Enums\ProductType;
use App\Models\Api\ApiKey;
use App\Models\Licensing\License;
use App\Models\Product;
use App\Models\Products\Plan;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

beforeEach(function () {
    Route::middleware('api.key')->get('/api/rate-test', function () {
        return response()->json(['ok' => true]);
    });
});

function createApiProduct(): Product
{
    return Product::create([
        'slug' => 'api-rate-test',
        'title' => 'API Rate Test',
        'type' => ProductType::ApiService,
        'price' => 10,
        'status' => 'Published',
        'default_license' => LicenseType::ApiSubscription,
        'requires_auth' => false,
    ]);
}

function createPlan(Product $product, array $overrides = []): Plan
{
    return Plan::create(array_merge([
        'product_id' => $product->id,
        'slug' => 'basic',
        'name' => 'Basic',
        'price_cents' => 1000,
        'billing_cycle' => 'monthly',
        'requests_per_minute' => 10,
        'is_active' => true,
    ], $overrides));
}

function createLicense(Product $product, Plan $plan, User $user): License
{
    return License::create([
        'product_id' => $product->id,
        'plan_id' => $plan->id,
        'user_id' => $user->id,
        'type' => LicenseType::ApiSubscription,
        'is_active' => true,
        'usage_count' => 0,
    ]);
}

function createApiKey(Product $product, Plan $plan, License $license, User $user, array $overrides = []): array
{
    $generated = ApiKey::generateKey();

    ApiKey::create(array_merge([
        'product_id' => $product->id,
        'license_id' => $license->id,
        'plan_id' => $plan->id,
        'user_id' => $user->id,
        'key_hash' => $generated['hash'],
        'key_prefix' => $generated['prefix'],
        'requests_this_month' => 0,
        'requests_today' => 0,
        'is_active' => true,
        'environment' => 'prod',
    ], $overrides));

    return $generated;
}

it('rate limits requests per minute for an api key', function () {
    $user = User::factory()->create();

    $product = createApiProduct();
    $plan = createPlan($product, ['requests_per_minute' => 2]);
    $license = createLicense($product, $plan, $user);
    $generated = createApiKey($product, $plan, $license, $user, [
        'allowed_domains' => ['example.com'],
    ]);

    $headers = [
        'Authorization' => 'Bearer '.$generated['key'],
        'Origin' => 'https://example.com',
    ];

    $this->getJson('/api/rate-test', $headers)->assertOk();
    $this->getJson('/api/rate-test', $headers)->assertOk();

    $this->getJson('/api/rate-test', $headers)
        ->assertStatus(429)
        ->assertJsonStructure(['message', 'retry_after']);
});

it('allows localhost origin for dev keys', function () {
    $user = User::factory()->create();
    $product = createApiProduct();
    $plan = createPlan($product);
    $license = createLicense($product, $plan, $user);
    $generated = createApiKey($product, $plan, $license, $user, [
        'environment' => 'dev',
    ]);

    $this->getJson('/api/rate-test', [
        'Authorization' => 'Bearer '.$generated['key'],
        'Origin' => 'http://localhost:3000',
    ])->assertOk();
});

it('rejects localhost origin for prod keys without explicit domains', function () {
    $user = User::factory()->create();
    $product = createApiProduct();
    $plan = createPlan($product);
    $license = createLicense($product, $plan, $user);
    $generated = createApiKey($product, $plan, $license, $user, [
        'environment' => 'prod',
    ]);

    $this->getJson('/api/rate-test', [
        'Authorization' => 'Bearer '.$generated['key'],
        'Origin' => 'http://localhost:3000',
    ])->assertStatus(403);
});

it('allows multiple domains and rejects non-listed domain', function () {
    $user = User::factory()->create();
    $product = createApiProduct();
    $plan = createPlan($product);
    $license = createLicense($product, $plan, $user);
    $generated = createApiKey($product, $plan, $license, $user, [
        'allowed_domains' => ['alpha.com', 'beta.com'],
    ]);

    $this->getJson('/api/rate-test', [
        'Authorization' => 'Bearer '.$generated['key'],
        'Origin' => 'https://beta.com',
    ])->assertOk();

    $this->getJson('/api/rate-test', [
        'Authorization' => 'Bearer '.$generated['key'],
        'Origin' => 'https://gamma.com',
    ])->assertStatus(403);
});

it('enforces plan domain limits', function () {
    $user = User::factory()->create();
    $product = createApiProduct();
    $plan = createPlan($product, ['limits' => ['domains' => 1]]);
    $license = createLicense($product, $plan, $user);
    $generated = ApiKey::generateKey();

    expect(fn () => ApiKey::create([
        'product_id' => $product->id,
        'license_id' => $license->id,
        'plan_id' => $plan->id,
        'user_id' => $user->id,
        'key_hash' => $generated['hash'],
        'key_prefix' => $generated['prefix'],
        'allowed_domains' => ['alpha.com', 'beta.com'],
        'environment' => 'prod',
        'is_active' => true,
    ]))->toThrow(ValidationException::class);
});
