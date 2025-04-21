<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('identifier')->unique()->nullable();
            $table->unsignedBigInteger('payment_id');
            $table->unsignedBigInteger('item_id');
            $table->string('status');
            $table->timestamp('status_changed_at')->nullable();
            $table->unsignedBigInteger('vending_machine_id')->nullable();

            $table->timestamps();

            $table->foreign('payment_id')->references('id')->on('payments');
            $table->foreign('item_id')->references('id')->on('items');
            $table->foreign('vendor_machine_id')->references('id')->on('vendor_machines');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
