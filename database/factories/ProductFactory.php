<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $productName = $this->faker->words(3, true); // e.g., "Wireless Bluetooth Speaker"
        return [
            'category_id'       => 3,
            'brand_id'          => 1,
            'vendor_id'         => 0,
            'admin_id'          => 1,
            'admin_type'        => 'superadmin',
            'product_name'      => ucfirst($productName),
            'product_code'      => strtoupper($this->faker->unique()->bothify('PROD-??#####')),
            'product_color'     => $this->faker->safeColorName,
            'product_price'     => $this->faker->randomFloat(2, 500, 50000), // 500 to 50,000
            'product_discount'  => $this->faker->randomFloat(2, 0, 50), // 0% to 50%
            'product_weight'    => $this->faker->numberBetween(100, 5000), // grams
            'product_image'     => null, // aap khud dalenge
            'product_video'     => null, // aap khud dalenge
            'group_code'        => $this->faker->optional()->regexify('[A-Z]{3}[0-9]{3}'), // e.g., ABC123
            'description'       => $this->faker->paragraphs(3, true),
            'meta_title'        => $this->faker->sentence(6),
            'meta_keywords'     => implode(', ', $this->faker->words(5)),
            'meta_description'  => $this->faker->sentence(15),
            'stock'             => $this->faker->numberBetween(0, 100),
            'stock_status'      => $this->faker->randomElement(['In Stock', 'Out of Stock']),
            'is_featured'       => $this->faker->randomElement(['No', 'Yes']),
            'status'            => 1,
        ];
    }
}
