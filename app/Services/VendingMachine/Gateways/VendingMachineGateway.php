<?php

namespace App\Services\VendingMachine\Gateways;

use App\Models\Order;
use App\Services\VendingMachine\Exceptions\VendingMachineInquiryFailedException;
use App\Services\VendingMachine\Request\InquiryItemRequest;
use App\Services\VendingMachine\Request\OrderItemRequest;
use Illuminate\Http\Client\RequestException;
use Throwable;

class VendingMachineGateway
{
    public function __construct(
        private readonly OrderItemRequest $orderItemRequest,
        private readonly InquiryItemRequest $inquiryItemRequest
    )
    {
    }

    /** @throws VendingMachineInquiryFailedException */
    public function orderItem(string $vendingMachineSlug, string $itemSlug): bool
    {
        try {
            $hasSubmitted = $this->orderItemRequest->send($vendingMachineSlug, $itemSlug);

        } catch (RequestException $requestException) {
            throw new VendingMachineInquiryFailedException($requestException->response->body());
        } catch (Throwable $e) {
            throw new VendingMachineInquiryFailedException($e->getMessage());
        }

        return $hasSubmitted;
    }

    /** @throws VendingMachineInquiryFailedException */
    public function inquiryOrderStatus(string $vendorMachineSlug): string
    {
        try {
            $status = $this->inquiryItemRequest->send($vendorMachineSlug);

        } catch (RequestException $requestException) {
            throw new VendingMachineInquiryFailedException($requestException->response->body());
        } catch (Throwable $e) {
            throw new VendingMachineInquiryFailedException($e->getMessage());
        }

        return $status;
    }
}
