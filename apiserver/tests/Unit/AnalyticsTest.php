<?php

use App\Models\Content\Activity;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Analytics', function () {

    describe('Activity Model', function () {
        beforeEach(function () {
            $this->product = Product::factory()->create();
            $this->user = User::factory()->create();
        });

        it('records product view', function () {
            $oldDownloads = $this->product->downloads;

            Activity::recordView($this->product, $this->user, '192.168.1.1');

            $activity = Activity::latest()->first();
            expect($activity->action_type)->toBe('view');
            expect($activity->product_id)->toBe($this->product->id);
            expect($activity->user_id)->toBe($this->user->id);
            expect($this->product->refresh()->downloads)->toBe($oldDownloads + 1);
        });

        it('records product download', function () {
            Activity::recordDownload($this->product, $this->user, '192.168.1.1');

            $activity = Activity::latest()->first();
            expect($activity->action_type)->toBe('download');
            expect($activity->product_id)->toBe($this->product->id);
        });

        it('records share with platform metadata', function () {
            Activity::recordShare($this->product, $this->user, 'facebook', '192.168.1.1');

            $activity = Activity::latest()->first();
            expect($activity->action_type)->toBe('share');
            expect($activity->metadata['platform'])->toBe('facebook');
        });

        it('records click with metadata', function () {
            Activity::recordClick($this->product, $this->user, 'demo_button', '192.168.1.1');

            $activity = Activity::latest()->first();
            expect($activity->action_type)->toBe('click');
            expect($activity->metadata['click_type'])->toBe('demo_button');
        });

        it('works without user', function () {
            Activity::recordView($this->product, null, '192.168.1.1');

            $activity = Activity::latest()->first();
            expect($activity->user_id)->toBeNull();
            expect($activity->product_id)->toBe($this->product->id);
        });

        it('relates to product', function () {
            $activity = Activity::factory()->for($this->product)->create();

            expect($activity->product->id)->toBe($this->product->id);
        });

        it('relates to user when present', function () {
            $activity = Activity::factory()
                ->for($this->product)
                ->for($this->user)
                ->create();

            expect($activity->user->id)->toBe($this->user->id);
        });
    });

    describe('Analytics Queries', function () {
        it('can get product views', function () {
            $product = Product::factory()->create();
            Activity::factory(5)->for($product)->create(['action_type' => 'view']);
            Activity::factory(3)->for($product)->create(['action_type' => 'download']);

            $views = Activity::where('product_id', $product->id)
                ->where('action_type', 'view')
                ->count();

            expect($views)->toBe(5);
        });

        it('can track activity by ip address', function () {
            $ip = '192.168.1.100';
            Activity::factory(4)->create(['ip_address' => $ip]);
            Activity::factory(2)->create(['ip_address' => '192.168.1.200']);

            $activities = Activity::where('ip_address', $ip)->get();

            expect($activities->count())->toBe(4);
        });
    });
});
