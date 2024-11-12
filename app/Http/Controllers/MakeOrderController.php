<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Services\OrdersService;

class MakeOrderController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(OrderRequest $request,OrdersService  $ordersService)
    {


        return [
            'success' => true,
        ];
    }
}
