<?php

namespace App\Http\Controllers\Customer\Order;


use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Order\OrderRequest;
use App\Http\Resources\OrderDetailsResource;
use App\Services\Inventory\Exceptions\InsufficientItemInInventoryException;
use App\Services\Order\OrderService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Throwable;

class OrderController extends Controller
{
    public function __construct(private readonly OrderService $orderService)
    {
    }

    public function __invoke(OrderRequest $request): JsonResponse
    {
        try {
            $attributes = $request->validated();

            $order = $this->orderService->initOrder($attributes['item_id'], $attributes['request_code']);
        }
        catch (InsufficientItemInInventoryException $e){
            return $this->error(__('insufficient_item_inventory'));
        }
        catch (ModelNotFoundException $e){
            return $this->error(__('payment_is_required'));
        }
        catch (Throwable $th){
            return $this->error();
        }

        return $this->success(OrderDetailsResource::make($order));
    }
}
