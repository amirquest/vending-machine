<?php

namespace App\Http\Controllers\Customer\Payment;


use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentPurchaseResource;
use App\Services\Payment\PaymentService;
use Illuminate\Http\JsonResponse;
use Throwable;

class
PurchasePaymentController extends Controller
{
    public function __construct(private readonly PaymentService $paymentService)
    {
    }

    public function __invoke(): JsonResponse
    {
        try {
            $payment = $this->paymentService->purchase();

        } catch (Throwable $th) {
            return $this->error();
        }

        return $this
            ->setMessage('Purchase Paid Successfully')
            ->success(PaymentPurchaseResource::make($payment));
    }
}
