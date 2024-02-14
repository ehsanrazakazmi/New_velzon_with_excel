<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $plans = [
            [
                'plan_id' => 'plan_PKw62j4AjYOliA',
                'name' => 'Basic',
                'billing_method' => 'week',
                'interval_count' => 1,
                'price' => 500,
                'currency' => 'usd',
            ],
            [
                'plan_id' => 'plan_PLEWjUTL1ZXsV0',
                'name' => 'professional',
                'billing_method' => 'month',
                'interval_count' => 1,
                'price' => 1000,
                'currency' => 'usd',
            ],
            [
                'plan_id' => 'plan_PLEjMt3cCNTTkL',
                'name' => 'enterprise',
                'billing_method' => 'year',
                'interval_count' => 1,
                'price' => 1500,
                'currency' => 'usd',
            ],

        ];

        foreach ($plans as $plan) {
            Plan::create($plan);
        }
    }
}
