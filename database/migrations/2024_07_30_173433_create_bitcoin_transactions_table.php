<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bitcoin_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('bitcoin_address');
            $table->decimal('amount', 16, 8);
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->string('webhook_id')->nullable()->unique();
            $table->string('webhook_url')->nullable();
            $table->integer('confirmations')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bitcoin_transactions');
    }
};
