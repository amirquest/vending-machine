<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('vending_machine_id');
            $table->unsignedBigInteger('quantity');

            $table->timestamps();

            $table->foreign('item_id')->references('id')->on('items');
            $table->foreign('vending_machine_id')->references('id')->on('vending_machines');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};
