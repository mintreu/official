<?php

namespace Database\Seeders;

use App\Enums\IntegrationType;
use App\Models\Integration;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class IntegrationSeeder extends Seeder
{
    public function run(): void
    {
        $integrations = [
            [
                'slug' => 'native',
                'name' => 'Native Manual Gateway',
                'type' => IntegrationType::Payment,
                'credentials' => null,
                'settings' => [
                    'driver' => 'native',
                    'supports_refund' => true,
                    'supports_webhook' => false,
                    'supports_manual_settlement' => true,
                    'supports_cash_entry' => true,
                    'supported_currencies' => ['BDT', 'INR', 'USD'],
                    'supported_countries' => ['BD', 'IN', 'US'],
                    'notes' => 'Used for testing, cash collection, and back-office manual payment entry.',
                ],
                'is_sandbox' => true,
                'is_active' => true,
                'is_default' => true,
            ],
            [
                'slug' => 'cashfree',
                'name' => 'Cashfree',
                'type' => IntegrationType::Payment,
                'credentials' => [
                    'key' => config('services.payment.cashfree.key') ?? env('CASHFREE_KEY'),
                    'secret' => config('services.payment.cashfree.secret') ?? env('CASHFREE_SECRET'),
                    'webhook_secret' => env('CASHFREE_WEBHOOK_SECRET'),
                ],
                'settings' => [
                    'driver' => 'cashfree',
                    'supports_refund' => true,
                    'supports_webhook' => true,
                    'supported_currencies' => ['INR'],
                    'supported_countries' => ['IN'],
                ],
                'is_sandbox' => (bool) (config('services.payment.cashfree.sandbox') ?? env('CASHFREE_SANDBOX', true)),
                'is_active' => true,
                'is_default' => false,
            ],
            [
                'slug' => 'stripe',
                'name' => 'Stripe',
                'type' => IntegrationType::Payment,
                'credentials' => [
                    'api_key' => config('services.stripe.key'),
                    'api_secret' => config('services.stripe.secret'),
                    'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
                ],
                'settings' => [
                    'driver' => 'stripe',
                    'supports_refund' => true,
                    'supports_webhook' => true,
                    'supported_currencies' => ['USD', 'EUR', 'GBP'],
                ],
                'is_sandbox' => (bool) config('services.stripe.sandbox', false),
                'is_active' => true,
                'is_default' => false,
            ],
            [
                'slug' => 'razorpay',
                'name' => 'Razorpay',
                'type' => IntegrationType::Payment,
                'credentials' => [
                    'key_id' => env('RAZORPAY_KEY_ID'),
                    'key_secret' => env('RAZORPAY_KEY_SECRET'),
                    'webhook_secret' => env('RAZORPAY_WEBHOOK_SECRET'),
                ],
                'settings' => [
                    'driver' => 'razorpay',
                    'supports_refund' => true,
                    'supports_webhook' => true,
                    'supported_currencies' => ['INR'],
                    'supported_countries' => ['IN'],
                ],
                'is_sandbox' => (bool) env('RAZORPAY_SANDBOX', true),
                'is_active' => true,
                'is_default' => false,
            ],
        ];

        foreach ($integrations as $data) {
            $integration = Integration::firstOrNew(['slug' => $data['slug']]);
            $integration->uuid ??= (string) Str::uuid();
            $integration->fill($data);
            $integration->save();
        }
    }
}
