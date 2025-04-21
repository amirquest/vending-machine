<?php

namespace App\Services\VendingMachine\Request;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class OrderItemRequest
{
    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function send(string $vendorMachineSlug, string $itemSlug): bool
    {
        $url = config('vendor-machine.base_url') . "/$vendorMachineSlug";
        $response = Http::asJson()
            ->acceptJson()
            ->post($url, [
                'item' => $itemSlug,
            ]);

        if ($response->failed()) {
            $response->throw();
        }

        return true;
    }
}
