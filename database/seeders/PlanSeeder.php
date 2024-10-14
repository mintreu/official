<?php

namespace Database\Seeders;

use App\Models\Subscription\Plan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $plans = $this->getAllPlans();

        foreach ($plans as $plan) {
            Plan::create($plan);
        }
    }

    private function getAllPlans()
    {
        return  [
            [
                'name' => 'Free Plan',
                'url' => 'free-plan',
                'base_price' => 0.00,
                'hsn_code' => null,
                'tax_percent' => 0.00,
                'tax_amount' => 0.00,
                'price' => 0.00,
                'per_month_limit' => 100,
                'auth_type' => 'Basic',
                'support_type' => 'Community',
                'documentation_type' => 'Limited',
                'desc' => 'A basic plan suitable for individuals or small projects. Limited features with no cost.',
                'features' => [
                    'rate_limit' => '100',
                    'authentication' => 'Basic',
                    'support' => 'Community',
                    'documentation' => 'Limited',
                    'data_security' => 'Basic',
                    'analytics_reporting' => 'Basic',
                    'plugin_support' => false,
                    'upgradable' => true,
                ],
                'visible_on_front' => true,
            ],
            [
                'name' => 'Basic Plan',
                'url' => 'basic-plan',
                'base_price' => 19.00,
                'hsn_code' => null,
                'tax_percent' => 5.00,
                'tax_amount' => 0.95,
                'price' => 19.95,
                'per_month_limit' => 1000,
                'auth_type' => 'Basic',
                'support_type' => 'Standard',
                'documentation_type' => 'Basic',
                'desc' => 'A step up from the Free Plan with additional features for small to medium-sized projects.',
                'features' => [
                    'rate_limit' => '1000',
                    'authentication' => 'Basic',
                    'support' => 'Standard',
                    'documentation' => 'Basic',
                    'data_security' => 'Standard',
                    'analytics_reporting' => 'Standard',
                    'plugin_support' => false,
                    'upgradable' => true,
                ],
                'visible_on_front' => true,
            ],
            [
                'name' => 'Standard Plan',
                'url' => 'standard-plan',
                'base_price' => 49.00,
                'hsn_code' => null,
                'tax_percent' => 10.00,
                'tax_amount' => 4.90,
                'price' => 53.90,
                'per_month_limit' => 10000,
                'auth_type' => 'OAuth 2.0',
                'support_type' => 'Priority',
                'documentation_type' => 'Enhanced',
                'desc' => 'Ideal for growing businesses that need a balance of features and scalability.',
                'features' => [
                    'rate_limit' => '10000',
                    'authentication' => 'OAuth 2.0',
                    'support' => 'Priority',
                    'documentation' => 'Enhanced',
                    'data_security' => 'Advanced',
                    'analytics_reporting' => 'Advanced',
                    'plugin_support' => true,
                    'upgradable' => true,
                ],
                'is_recommended' => true,
                'visible_on_front' => true,
            ],
            [
                'name' => 'Professional Plan',
                'url' => 'professional-plan',
                'base_price' => 99.00,
                'hsn_code' => null,
                'tax_percent' => 15.00,
                'tax_amount' => 14.85,
                'price' => 113.85,
                'per_month_limit' => 50000,
                'auth_type' => 'OAuth 2.0 & API Keys',
                'support_type' => '24/7',
                'documentation_type' => 'Comprehensive',
                'desc' => 'Designed for enterprises with high usage needs and comprehensive support requirements.',
                'features' => [
                    'rate_limit' => '50000',
                    'authentication' => 'OAuth 2.0 & API Keys',
                    'support' => '24/7',
                    'documentation' => 'Comprehensive',
                    'data_security' => 'Advanced',
                    'analytics_reporting' => 'Advanced',
                    'plugin_support' => true,
                    'upgradable' => true,
                ],
                'visible_on_front' => true,
            ],
            [
                'name' => 'Enterprise Plan',
                'url' => 'enterprise-plan',
                'base_price' => 0.00, // Custom pricing
                'hsn_code' => null,
                'tax_percent' => 0.00,
                'tax_amount' => 0.00,
                'price' => 0.00, // Custom pricing
                'per_month_limit' => null, // Unlimited
                'auth_type' => 'Custom',
                'support_type' => 'Dedicated',
                'documentation_type' => 'Full',
                'desc' => 'Tailored solutions for large enterprises with bespoke requirements and unlimited usage.',
                'features' => [
                    'rate_limit' => 'Unlimited',
                    'authentication' => 'Custom',
                    'support' => 'Dedicated',
                    'documentation' => 'Full',
                    'data_security' => 'Enterprise',
                    'analytics_reporting' => 'Enterprise',
                    'plugin_support' => true,
                    'upgradable' => true,
                ],
                'is_enterprise' => true,
                'visible_on_front' => true,
            ],
        ];
    }


}
