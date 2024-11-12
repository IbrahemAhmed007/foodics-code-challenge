<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);

        $product = Product::factory()->create([
            'name' => 'Burger',
        ]);
        Ingredient::factory()
            ->hasAttached($product, ['ingredient_quantity' => 150])
            ->create([
                'name' => 'Beef',
                'available_quantity' => 20000,
                'alert_quantity' => 10000
            ]);

        Ingredient::factory()
            ->hasAttached($product, ['ingredient_quantity' => 30])
            ->create([
                'name' => 'Cheese',
                'available_quantity' => 5000,
                'alert_quantity' => 2500
            ]);

        Ingredient::factory()
            ->hasAttached($product, ['ingredient_quantity' => 20])
            ->create([
                'name' => 'Onion',
                'available_quantity' => 1000,
                'alert_quantity' => 500
            ]);
    }
}
