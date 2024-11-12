<?php

namespace App\Http\Controllers\API\V1\Orders;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Services\OrdersService;

class MakeOrderController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(OrderRequest $request)
    {
        $orderService = app(OrdersService::class, ['orderProducts' => $request->validated()['products']]);
        if ($orderService->hasSufficientIngredientAmount() && $orderService->makeOrder()) {
            return [
                'success' => true,
            ];
        }

        return [
            'success' => false,
        ];
    }
}
