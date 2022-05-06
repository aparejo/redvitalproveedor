<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\producto>
 */
class ProductoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'sku_provee'=> $this->faker->sentence(),
            'nombre'=>$this->faker->text(),
            'barras'=>$this->faker->text(),
            'status'=>$this->faker->text(),
            'cantidad_empaque'=>$this->faker->randomNumber()

        ];
    }
}
