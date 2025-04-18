<?php

namespace App\Http\Controllers\Customer\Basket;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Basket\AddItemRequest;
use Throwable;

class BasketController extends Controller
{
    public function __construct()
    {
    }

    public function addItem(AddItemRequest $request)
    {
        try {

        }
        catch (Throwable $th){

            return $this->error();
        }

        return $this->success();
    }

    public function removeItem()
    {

    }
}
