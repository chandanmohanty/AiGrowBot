<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name'            => 'Free',
                'slug'            => 'free',
                'tagline'         => '7-Day Free Trial',
                'description'     => 'Try everything risk-free for 7 days. No credit card required. Get started instantly.',
                'price_monthly'   => 0,
                'price_yearly'    => 0,
                'currency'        => 'INR',
                'save_percent'    => null,
                'cta_label'       => 'Start Free Trial',
                'cta_url'         => 'https://app.aigrowbot.com/register',
                'is_active'       => true,
                'is_popular'      => false,
                'is_free_trial'   => true,
                'free_trial_days' => 7,
                'sort_order'      => 1,
                'features'        => [
                    ['text' => 'Save contacts',              'included' => true],
                    ['text' => 'Embedded Signup',            'included' => true],
                    ['text' => 'Manual Bot Flow',            'included' => true],
                    ['text' => 'AI Chat Bot',                'included' => false],
                    ['text' => 'Meta Lead Capture',          'included' => false],
                    ['text' => 'E-Commerce Integrations',    'included' => false],
                    ['text' => 'Dedicated Support',          'included' => false],
                    ['text' => '24/7 email support',         'included' => true],
                ],
            ],
            [
                'name'          => 'Start-up',
                'slug'          => 'start-up',
                'tagline'       => null,
                'description'   => 'Ideal for startups beginning campaigns and implementing essential marketing automation tools.',
                'price_monthly' => 999,
                'price_yearly'  => 7192,
                'currency'      => 'INR',
                'save_percent'  => 40,
                'cta_label'     => 'Register',
                'cta_url'       => 'https://app.aigrowbot.com/register',
                'is_active'     => true,
                'is_popular'    => false,
                'is_free_trial' => false,
                'sort_order'    => 2,
                'features'      => [
                    ['text' => 'Save contacts',           'included' => true],
                    ['text' => 'Embedded Signup',         'included' => true],
                    ['text' => 'Manual Bot Flow',         'included' => true],
                    ['text' => 'AI Chat Bot',             'included' => false],
                    ['text' => 'Meta Lead Capture',       'included' => false],
                    ['text' => 'E-Commerce Integrations', 'included' => false],
                    ['text' => 'Dedicated Support',       'included' => false],
                    ['text' => '24/7 email support',      'included' => true],
                ],
            ],
            [
                'name'          => 'Business',
                'slug'          => 'business',
                'tagline'       => 'Most Popular',
                'description'   => 'For growing businesses looking to scale campaigns and streamline automation.',
                'price_monthly' => 2999,
                'price_yearly'  => 21592,
                'currency'      => 'INR',
                'save_percent'  => 40,
                'cta_label'     => 'Register',
                'cta_url'       => 'https://app.aigrowbot.com/register',
                'is_active'     => true,
                'is_popular'    => true,
                'is_free_trial' => false,
                'sort_order'    => 3,
                'features'      => [
                    ['text' => 'Save unlimited contacts', 'included' => true],
                    ['text' => 'Embedded Signup',         'included' => true],
                    ['text' => 'Manual Bot Flow',         'included' => true],
                    ['text' => 'AI Chat Bot',             'included' => true],
                    ['text' => 'Meta Lead Capture',       'included' => true],
                    ['text' => 'E-Commerce Integrations', 'included' => false],
                    ['text' => 'Dedicated Support',       'included' => true],
                    ['text' => '24/7 email support',      'included' => true],
                ],
            ],
            [
                'name'          => 'Enterprise',
                'slug'          => 'enterprise',
                'tagline'       => null,
                'description'   => 'Perfect for enterprise aiming to expand campaigns and automate with ease.',
                'price_monthly' => 5999,
                'price_yearly'  => 43192,
                'currency'      => 'INR',
                'save_percent'  => 40,
                'cta_label'     => 'Register',
                'cta_url'       => 'https://app.aigrowbot.com/register',
                'is_active'     => true,
                'is_popular'    => false,
                'is_free_trial' => false,
                'sort_order'    => 4,
                'features'      => [
                    ['text' => 'Save unlimited contacts', 'included' => true],
                    ['text' => 'Embedded Signup',         'included' => true],
                    ['text' => 'Manual Bot Flow',         'included' => true],
                    ['text' => 'AI Chat Bot',             'included' => true],
                    ['text' => 'Meta Lead Capture',       'included' => true],
                    ['text' => 'E-Commerce Integrations', 'included' => true],
                    ['text' => 'Dedicated Support',       'included' => true],
                    ['text' => '24/7 email support',      'included' => true],
                ],
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::updateOrCreate(['slug' => $plan['slug']], $plan);
        }
    }
}
