<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vending_machines', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('status');
            $table->timestamp('status_changed_at')->nullable();
            $table->unsignedBigInteger('healthy_retry_interval')->nullable();
            $table->unsignedBigInteger('unhealthy_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vending_machines');
    }
};
