<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'vendor_machine_id',
        'quantity',
    ];

    public function item(): HasOne
    {
        return $this->hasOne(Item::class);
    }

    public function vendorMachine(): HasOne
    {
        return $this->hasOne(VendorMachine::class);
    }
}
