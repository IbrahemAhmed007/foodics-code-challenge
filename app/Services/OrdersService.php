<?php

namespace App\Services;

use App\Models\Ingredient;
use App\Models\IngredientProduct;
use App\Models\Order;
use App\Models\OrderDetails;
use DB;
use Exception;
use Illuminate\Support\Collection;

class OrdersService
{
    private array $productIds;
    private Collection $ingredientProducts;
    private Collection $usedIngredientQuantity;
    private Collection $ingredient;
    private Collection $orderDetailsCollection;

    public function __construct(array $orderProducts)
    {
        $this->orderDetailsCollection = collect($orderProducts);
        $this->productIds = $this->orderDetailsCollection->pluck('product_id')->toArray();
        $this->setIngredientProduct();
        $this->setUsedIngredient();
    }

    private function setIngredientProduct(): void
    {
        $this->ingredientProducts = IngredientProduct::
        select(['ingredient_id', 'product_id', 'ingredient_quantity'])
            ->whereIn('product_id', $this->productIds)
            ->get();
    }

    private function setUsedIngredient(): void
    {
        $this->usedIngredientQuantity = $this->ingredientProducts->map(function ($ingredientProduct) {
            $ingredientProduct->total_qty =
                $ingredientProduct->ingredient_quantity * $this->orderDetailsCollection->where('product_id', $ingredientProduct['product_id'])->first()['quantity'];
            return $ingredientProduct;
        })->groupBy('ingredient_id')->map(function ($group) {
            return [
                'ingredient_id' => $group->first()['ingredient_id'],
                'total_qty' => $group->sum('total_qty'),
            ];
        });

        $this->ingredient = Ingredient::whereHas('products', function ($query) {
            $query->whereIn('products.id', $this->productIds);
        })->get();
    }

    public function hasSufficientIngredientAmount(): bool
    {
        return !$this->ingredient->filter(function ($ingredient) {
            return $ingredient->available_quantity < $this->usedIngredientQuantity->where('ingredient_id', $ingredient->id)->first()['total_qty'];
        })->count();
    }

    public function makeOrder(): bool
    {
        try {
            DB::beginTransaction();

            $order = Order::create([]);

            $this->ingredient->each(function (Ingredient $ingredient) {
                $ingredient->decrement('available_quantity', $this->usedIngredientQuantity->where('ingredient_id', $ingredient->id)->first()['total_qty']);
            });

            $orderDetails = [];
            foreach ($this->orderDetailsCollection as $orderItem) {
                $orderDetail = [
                    'order_id' => $order->id,
                    'product_id' => $orderItem['product_id'],
                    'quantity' => $orderItem['quantity']
                ];

                $orderDetails[] = $orderDetail;
            }

            OrderDetails::insert($orderDetails);


            DB::commit();
            return true;
        } catch (Exception) {
            DB::rollBack();
            return false;
        }
    }
}
