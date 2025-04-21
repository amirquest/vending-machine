<?php

namespace App\Http\Controllers\Customer\Item;

use App\Http\Controllers\Controller;
use App\Http\Resources\ItemsResource;
use App\Repositories\ItemRepository;
use Illuminate\Http\JsonResponse;
use Throwable;

class PreviewItemsController extends Controller
{
    public function __construct(private readonly ItemRepository $itemRepository)
    {
    }

    public function __invoke(): JsonResponse
    {
        try {
            $items = $this->itemRepository->getAll();
        }
        catch (Throwable $th){
            return $this->error();
        }

        return $this->success(ItemsResource::collection($items));
    }
}
