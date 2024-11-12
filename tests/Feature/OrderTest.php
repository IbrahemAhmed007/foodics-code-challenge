<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_empty_order(): void
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/api/v1/orders');

        $response->assertStatus(422);
    }

    public function test_data_migrated(): void
    {
        $this->assertDatabaseCount('products', 1);
    }

    public function test_create_order_successfully(): void
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/api/v1/orders',
            [
                'products' => [
                    [
                        'product_id' => 1,
                        'quantity' => 1,
                    ]
                ]

            ]);

        $response->assertStatus(201)->assertJson([])->assertJson([
            'success' => true,
        ]);
    }

    public function test_create_order_for_insufficient_order_items(): void
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/api/v1/orders',
            [
                'products' => [
                    [
                        'product_id' => 1,
                        'quantity' => 500,
                    ]
                ]

            ]);

        $response->assertStatus(200)->assertJson([])->assertJson([
            'success' => false,
        ]);
    }
}
