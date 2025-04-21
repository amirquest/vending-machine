<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\Order;
use App\Models\Payment;
use App\Models\VendingMachine;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if ($this->app->isLocal()) {
            $this->app->register(IdeHelperServiceProvider::class);
        }
    }


    public function boot(): void
    {
        $this->setRelationMorphMap();
    }

    private function setRelationMorphMap(): void
    {
        Relation::morphMap([
            'order' => Order::class,
            'inventory' => Inventory::class,
            'item' => Item::class,
            'vending_machine' => VendingMachine::class,
            'payment' => Payment::class,
        ]);
    }
}
