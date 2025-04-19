<?php

namespace App\Services\Payment;

use App\Enums\PaymentStatusEnum;
use App\Models\Payment;
use App\Repositories\PaymentRepository;
use Illuminate\Database\Eloquent\Model;

class PaymentService
{
    public function __construct(private readonly PaymentRepository $paymentRepository)
    {
    }

    public function purchase(): Model|Payment
    {
        $attributes = [
            'amount' => 1,
            'status' => PaymentStatusEnum::PAID(),
        ];

        /** @var Payment $persisted */
        return $this->paymentRepository->persist($attributes);
    }
}
