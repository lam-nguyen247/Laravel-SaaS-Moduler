<?php

namespace Database\Factories;

use App\Modules\Admin\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' =>  $this->faker->name(),
            'description' =>  $this->faker->paragraph(),
            'status' => 'active',
            'created_by' => '1',
            'updated_by' => '1',
        ];
    }
}
