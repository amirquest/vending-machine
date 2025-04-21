<?php

namespace App\Http\Resources;

use App\Models\Payment;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentPurchaseResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var Payment $payment */
        $payment = $this->resource;

        return [
            'request_code' => $payment->request_code,
        ];
    }
}
