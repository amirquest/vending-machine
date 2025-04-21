<?php

namespace Tests\Unit\Services\Order;

use App\Jobs\SendOrderToVendingMachineJob;
use App\Models\Order;
use App\Models\Payment;
use App\Repositories\InventoryRepository;
use App\Repositories\OrderRepository;
use App\Repositories\PaymentRepository;
use App\Services\Inventory\InventoryService;
use App\Services\Order\OrderService;
use App\Services\Order\UpdateOrderStatusService;
use App\Services\VendingMachine\Gateways\VendingMachineGateway;
use App\Services\VendingMachine\UpdateVendingMachineStatusService;
use App\Services\VendingMachine\VendingMachineService;
use Illuminate\Contracts\Bus\Dispatcher;
use PHPUnit\Framework\Attributes\Test;
use Tests\UnitTestCase;
use Mockery as M;

class OrderServiceTest extends UnitTestCase
{
    private ?OrderService $sut;

    private PaymentRepository|(M\MockInterface&M\LegacyMockInterface)|null $paymentRepoMock;
    private OrderRepository|(M\MockInterface&M\LegacyMockInterface)|null $orderRepoMock;
    private VendingMachineService|(M\MockInterface&M\LegacyMockInterface)|null $vendingMachineServiceMock;
    private InventoryRepository|(M\MockInterface&M\LegacyMockInterface)|null $inventoryRepoMock;
    private UpdateVendingMachineStatusService|(M\MockInterface&M\LegacyMockInterface)|null $updateVendingMachineStatusServiceMock;
    private InventoryService|(M\MockInterface&M\LegacyMockInterface)|null $inventoryServiceMock;
    private UpdateOrderStatusService|(M\MockInterface&M\LegacyMockInterface)|null $updateOrderStatusServiceMock;
    private VendingMachineGateway|(M\MockInterface&M\LegacyMockInterface)|null $vendingMachineGatewayMock;
    private Dispatcher|(M\MockInterface&M\LegacyMockInterface)|null $dispatcherMock;
    private Payment|(M\MockInterface&M\LegacyMockInterface)|null $paymentMock;
    private Order|(M\MockInterface&M\LegacyMockInterface)|null $orderMock;


    public function setUp(): void
    {
        parent::setUp();

        $this->paymentRepoMock = M::mock(PaymentRepository::class);
        $this->orderRepoMock = M::mock(OrderRepository::class);
        $this->inventoryRepoMock = M::mock(InventoryRepository::class);

        $this->vendingMachineServiceMock = M::mock(VendingMachineService::class);
        $this->updateVendingMachineStatusServiceMock = M::mock(UpdateVendingMachineStatusService::class);
        $this->inventoryServiceMock = M::mock(InventoryService::class);
        $this->updateOrderStatusServiceMock = M::mock(UpdateOrderStatusService::class);
        $this->vendingMachineGatewayMock = M::mock(VendingMachineGateway::class);

        $this->paymentMock = M::mock(Payment::class);
        $this->orderMock = M::mock(Order::class);

        $this->dispatcherMock = M::mock(Dispatcher::class);

        $this->sut = new OrderService(
            $this->paymentRepoMock,
            $this->orderRepoMock,
            $this->inventoryRepoMock,
            $this->vendingMachineServiceMock,
            $this->inventoryServiceMock,
            $this->vendingMachineGatewayMock,
            $this->updateOrderStatusServiceMock,
            $this->updateVendingMachineStatusServiceMock,
            $this->dispatcherMock,
        );
    }

    #[Test]
    public function itCanInitOrderSuccessfully(): void
    {
        $this->paymentRepoMock
            ->expects('findByRequestCode')
            ->with('sample_request_code')
            ->andReturn($this->paymentMock);

        $this->inventoryServiceMock
            ->expects('checkInventoryHasItem')
            ->with(1122334455)
            ->andReturn();

        $this->paymentMock
            ->expects('getAttribute')
            ->with('id')
            ->andReturn(100);

        $this->orderRepoMock
            ->expects('persist')
            ->with(['payment_id' => 100, 'item_id' => 1122334455])
            ->andReturn($this->orderMock);

        $this->orderMock
            ->expects('getAttribute')
            ->with('id')
            ->andReturn(200);

        $this->dispatcherMock
            ->expects('dispatch')
            ->with(m::type(SendOrderToVendingMachineJob::class))
            ->andReturn();

        $this->sut->initOrder(1122334455, 'sample_request_code')  ;
    }
}
