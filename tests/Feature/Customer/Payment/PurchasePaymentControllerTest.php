<?php

namespace Tests\Feature\Customer\Payment;

use App\Enums\PaymentStatusEnum;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

class PurchasePaymentControllerTest extends FeatureTestCase
{
    #[Test]
    public function itCanPaySuccessfully(): void
    {
        $response = $this->post(route('customer.payment.pay'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                "succeed",
                "message",
                "results" => [
                    "request_code",
                ]
            ]);

        $this->assertDatabaseHas('payments', [
            'request_code' => $response->json('results.request_code'),
            'status' => PaymentStatusEnum::PAID(),
            'amount' => 1
        ]);
    }
}
