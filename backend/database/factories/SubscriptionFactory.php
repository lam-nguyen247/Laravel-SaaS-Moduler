<?php

namespace Database\Factories;

use App\Modules\Admin\Models\Subscription;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class SubscriptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Subscription::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => '1',
            'user_id' => '1',
            'status' => 'active',
            'created_by' => '1',
            'updated_by' => '1',
        ];
    }
}
