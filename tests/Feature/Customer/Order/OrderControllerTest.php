<?php

namespace Tests\Feature\Customer\Order;

use App\Enums\OrderStatusEnum;
use App\Jobs\SendOrderToVendingMachineJob;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\Payment;
use App\Models\VendingMachine;
use Illuminate\Support\Facades\Bus;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

class OrderControllerTest extends FeatureTestCase
{
    #[Test]
    public function itCanSubmitOrderSuccessfully(): void
    {
        Bus::fake(SendOrderToVendingMachineJob::class);

        $payment = Payment::factory()->create();

        $item = Item::factory()->create();

        $vendingMachine = VendingMachine::factory()->create();

        Inventory::factory()
            ->forItem($item)
            ->forVendingMachine($vendingMachine)
            ->create();

        $response = $this->post(route('customer.order.order'), [
            'item_id' => Item::query()->first()->id,
            'request_code' => $payment->id,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                "succeed",
                "message",
                "results" => [
                    "status",
                    "identifier",
                ]
            ]);

        $this->assertDatabaseHas('orders', [
            'identifier' => $response->json('results.identifier'),
            'payment_id' => $payment->id,
            'item_id' => $item->id,
            'status' => OrderStatusEnum::INIT(),
            'vending_machine_id' => $vendingMachine->id
        ]);

        Bus::assertDispatched(SendOrderToVendingMachineJob::class);
    }
}
