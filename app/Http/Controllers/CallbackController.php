<?php

namespace App\Http\Controllers;

use App\Services\Order\OrderService;
use Throwable;

class CallbackController extends Controller
{
    public function __construct(private readonly OrderService $orderService)
    {
    }

    public function __invoke(int $identifier)
    {
        try {
            $this->orderService->callback($identifier);
        }
        catch (Throwable $th){
            return $this->error();
        }

        return $this->success();
    }
}
