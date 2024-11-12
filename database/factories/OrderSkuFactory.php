<?php

namespace Database\Factories;

use App\Enums\OrderStatusEnum;
use App\Models\Sku;
use Illuminate\Database\Eloquent\Factories\Factory;
use Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderSku>
 */
class OrderSkuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $sku = Sku::with('product')->isRandomOrder()->first();
        
        return [
            'sku_id' => $sku->id,
            'product' => $sku->toJson(),
            'quantity' => $this->faker->randomDigitNot(0),
            'unitary_price' => $sku->price
        ];
    }
}
