<?php

use App\Enums\LicenseType;
use App\Models\License;
use App\Models\Product;
use App\Models\User;

describe('License Model', function () {
    beforeEach(function () {
        $this->product = Product::factory()->create(['is_payable' => true]);
        $this->user = User::factory()->create();
    });

    describe('isValid()', function () {
        it('returns true for active licenses without expiry', function () {
            $license = License::factory()
                ->for($this->product)
                ->for($this->user)
                ->create([
                    'is_active' => true,
                    'expires_at' => null,
                    'usage_count' => 0,
                    'max_usage' => null,
                ]);

            expect($license->isValid())->toBeTrue();
        });

        it('returns false for inactive licenses', function () {
            $license = License::factory()
                ->for($this->product)
                ->for($this->user)
                ->create([
                    'is_active' => false,
                ]);

            expect($license->isValid())->toBeFalse();
        });

        it('returns false for expired licenses', function () {
            $license = License::factory()
                ->for($this->product)
                ->for($this->user)
                ->create([
                    'is_active' => true,
                    'expires_at' => now()->subDay(),
                ]);

            expect($license->isValid())->toBeFalse();
        });

        it('returns false when max usage exceeded', function () {
            $license = License::factory()
                ->for($this->product)
                ->for($this->user)
                ->create([
                    'is_active' => true,
                    'expires_at' => null,
                    'max_usage' => 5,
                    'usage_count' => 5,
                ]);

            expect($license->isValid())->toBeFalse();
        });

        it('returns true when usage is within limits', function () {
            $license = License::factory()
                ->for($this->product)
                ->for($this->user)
                ->create([
                    'is_active' => true,
                    'expires_at' => null,
                    'max_usage' => 5,
                    'usage_count' => 3,
                ]);

            expect($license->isValid())->toBeTrue();
        });
    });

    describe('recordUsage()', function () {
        it('increments usage count for valid licenses', function () {
            $license = License::factory()
                ->for($this->product)
                ->for($this->user)
                ->create([
                    'is_active' => true,
                    'usage_count' => 0,
                    'max_usage' => null,
                    'first_used_at' => null,
                ]);

            expect($license->recordUsage())->toBeTrue();
            expect($license->refresh()->usage_count)->toBe(1);
            expect($license->first_used_at)->not->toBeNull();
            expect($license->last_used_at)->not->toBeNull();
        });

        it('returns false for invalid licenses', function () {
            $license = License::factory()
                ->for($this->product)
                ->for($this->user)
                ->create([
                    'is_active' => false,
                ]);

            expect($license->recordUsage())->toBeFalse();
        });

        it('stops recording usage after max usage reached', function () {
            $license = License::factory()
                ->for($this->product)
                ->for($this->user)
                ->create([
                    'is_active' => true,
                    'max_usage' => 2,
                    'usage_count' => 2,
                ]);

            expect($license->recordUsage())->toBeFalse();
            expect($license->refresh()->usage_count)->toBe(2);
        });
    });

    describe('License Types', function () {
        it('free single use license has no max usage', function () {
            $license = License::factory()
                ->for($this->product)
                ->for($this->user)
                ->create([
                    'license_type' => LicenseType::FREE_SINGLE_USE,
                ]);

            expect($license->license_type)->toBe(LicenseType::FREE_SINGLE_USE);
        });

        it('commercial 3 uses license enforces limit', function () {
            $license = License::factory()
                ->for($this->product)
                ->for($this->user)
                ->create([
                    'license_type' => LicenseType::COMMERCIAL_3_USES,
                    'max_usage' => 3,
                    'usage_count' => 3,
                ]);

            expect($license->isValid())->toBeFalse();
        });

        it('api subscription can have custom config', function () {
            $config = [
                'rate_limit' => '100 requests per hour',
                'duration_days' => 30,
            ];

            $license = License::factory()
                ->for($this->product)
                ->for($this->user)
                ->create([
                    'license_type' => LicenseType::API_SUBSCRIPTION,
                    'api_config' => $config,
                ]);

            expect($license->api_config)->toMatchArray($config);
        });
    });

    describe('relationships', function () {
        it('belongs to product', function () {
            $license = License::factory()
                ->for($this->product)
                ->for($this->user)
                ->create();

            expect($license->product->id)->toBe($this->product->id);
        });

        it('belongs to user', function () {
            $license = License::factory()
                ->for($this->product)
                ->for($this->user)
                ->create();

            expect($license->user->id)->toBe($this->user->id);
        });
    });
});
