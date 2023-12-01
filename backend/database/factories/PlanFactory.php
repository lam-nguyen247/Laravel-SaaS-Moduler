<?php

namespace Database\Factories;

use App\Modules\Admin\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class PlanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Plan::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => '1',
            'name' => $this->faker->name(),
            'code' => 'A',
            'price' => '1200',
            'created_by' => '1',
            'updated_by' => '1',
        ];
    }
}
