<?php

namespace App\Repositories;

use App\Enums\PaymentStatusEnum;
use App\Models\Payment;
use App\Repositories\Contracts\AbstractRepository;
use Illuminate\Database\Eloquent\Model;

class PaymentRepository extends AbstractRepository
{
    public function findByRequestCode(string $requestCode): Model|Payment
    {
        return $this->getQuery()
            ->where('request_code', $requestCode)
            ->where('status', PaymentStatusEnum::PAID())
            ->firstOrFail();
    }

    protected function instance(array $attributes = []): Payment
    {
        return new Payment($attributes);
    }
}
