<?php

use App\Enums\SourceProvider;
use App\Models\Product;
use App\Models\Products\ProductSource;
use App\Services\DownloadService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->service = new DownloadService;
});

describe('generateDownloadUrl', function () {
    it('generates a download URL with token', function () {
        $product = Product::factory()->create();
        $source = ProductSource::factory()->create([
            'product_id' => $product->id,
            'provider' => SourceProvider::GitHub,
            'source_url' => 'https://github.com/test/repo/archive/refs/tags/v1.zip',
        ]);

        $result = $this->service->generateDownloadUrl($source);

        expect($result)
            ->toHaveKeys(['url', 'token', 'expires_at'])
            ->and($result['url'])->toContain('/api/download/')
            ->and($result['token'])->not->toBeEmpty();
    });

    it('stores token data in cache', function () {
        $product = Product::factory()->create();
        $source = ProductSource::factory()->create([
            'product_id' => $product->id,
            'provider' => SourceProvider::GitHub,
        ]);

        $result = $this->service->generateDownloadUrl($source);

        $cachedData = Cache::get('download_token:'.$result['token']);

        expect($cachedData)
            ->toBeArray()
            ->and($cachedData['source_id'])->toBe($source->id)
            ->and($cachedData['product_id'])->toBe($product->id);
    });
});

describe('validateAndGetDownloadUrl', function () {
    it('returns null for invalid token', function () {
        $result = $this->service->validateAndGetDownloadUrl('invalid-token');

        expect($result)->toBeNull();
    });

    it('returns null for expired token', function () {
        $result = $this->service->validateAndGetDownloadUrl(base64_encode('fake:token'));

        expect($result)->toBeNull();
    });
});

describe('token priority', function () {
    it('uses source encrypted_token over config token', function () {
        $product = Product::factory()->create();
        $source = ProductSource::factory()->create([
            'product_id' => $product->id,
            'provider' => SourceProvider::GitHub,
            'source_url' => 'https://api.github.com/repos/owner/repo/zipball/v1',
        ]);

        // Set a token on the source
        $source->setToken('source-specific-token');
        $source->save();

        // Set a different token in config
        config(['services.github.token' => 'config-fallback-token']);

        // Verify the source token is retrieved correctly
        expect($source->getToken())->toBe('source-specific-token');
    });

    it('falls back to config token when source has no token', function () {
        $product = Product::factory()->create();
        $source = ProductSource::factory()->create([
            'product_id' => $product->id,
            'provider' => SourceProvider::GitHub,
            'source_url' => 'https://api.github.com/repos/owner/repo/zipball/v1',
            'encrypted_token' => null,
        ]);

        config(['services.github.token' => 'config-fallback-token']);

        expect($source->getToken())->toBeNull();
        expect(config('services.github.token'))->toBe('config-fallback-token');
    });
});

describe('GitHub URL conversion', function () {
    it('converts archive URL to API zipball URL for private repo', function () {
        Http::fake([
            'api.github.com/*' => Http::response([], 302, [
                'Location' => 'https://codeload.github.com/test/archive.zip',
            ]),
        ]);

        $product = Product::factory()->create();
        $source = ProductSource::factory()->create([
            'product_id' => $product->id,
            'provider' => SourceProvider::GitHub,
            'source_url' => 'https://github.com/owner/repo/archive/refs/tags/v1.0.0.zip',
            'metadata' => ['owner' => 'owner', 'repo' => 'repo'],
        ]);
        $source->setToken('test-token');
        $source->save();

        $downloadData = $this->service->generateDownloadUrl($source);
        $result = $this->service->validateAndGetDownloadUrl($downloadData['token']);

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'api.github.com/repos/owner/repo/zipball/v1.0.0');
        });
    });

    it('handles API URL directly', function () {
        Http::fake([
            'api.github.com/*' => Http::response([], 302, [
                'Location' => 'https://codeload.github.com/test/archive.zip',
            ]),
        ]);

        $product = Product::factory()->create();
        $source = ProductSource::factory()->create([
            'product_id' => $product->id,
            'provider' => SourceProvider::GitHub,
            'source_url' => 'https://api.github.com/repos/mintreu/test-repo/zipball/v1',
            'metadata' => ['owner' => 'mintreu', 'repo' => 'test-repo'],
        ]);
        $source->setToken('test-token');
        $source->save();

        $downloadData = $this->service->generateDownloadUrl($source);
        $result = $this->service->validateAndGetDownloadUrl($downloadData['token']);

        expect($result)->not->toBeNull()
            ->and($result['redirect_url'])->toBe('https://codeload.github.com/test/archive.zip');
    });
});

describe('public repo download', function () {
    it('returns source URL directly for public repo without token', function () {
        $product = Product::factory()->create();
        $source = ProductSource::factory()->create([
            'product_id' => $product->id,
            'provider' => SourceProvider::GitHub,
            'source_url' => 'https://github.com/laravel/laravel/archive/refs/tags/v11.0.0.zip',
            'encrypted_token' => null,
        ]);

        config(['services.github.token' => null]);

        $downloadData = $this->service->generateDownloadUrl($source);
        $result = $this->service->validateAndGetDownloadUrl($downloadData['token']);

        expect($result)->not->toBeNull()
            ->and($result['redirect_url'])->toBe('https://github.com/laravel/laravel/archive/refs/tags/v11.0.0.zip');
    });
});
