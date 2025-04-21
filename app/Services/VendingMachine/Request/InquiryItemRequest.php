<?php

namespace App\Services\VendingMachine\Request;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class InquiryItemRequest
{
    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function send(string $vendorMachineSlug): string
    {
        $response = Http::asJson()
            ->acceptJson()
            ->get(config('vendor-machine.base_url') . "/$vendorMachineSlug");

        if ($response->failed()) {
            $response->throw();
        }

        return $response->json('status');
    }
}
