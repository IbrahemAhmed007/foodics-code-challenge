<?php

namespace App\Http\Controllers\API\V1\Orders;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Services\OrdersService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class MakeOrderController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(OrderRequest $request) : JsonResponse
    {
        $orderService = app(OrdersService::class, ['orderProducts' => $request->validated()['products']]);
        if ($orderService->hasSufficientIngredientAmount() && $orderService->makeOrder()) {
            return response()->json([
                'success' => true,
            ], ResponseAlias::HTTP_CREATED);
        }

        return response()->json([
            'success' => false,
        ]);
    }
}
