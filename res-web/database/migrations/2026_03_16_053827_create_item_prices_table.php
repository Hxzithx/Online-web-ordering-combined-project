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
        Schema::create('item_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->unsignedBigInteger('portion_id')->nullable();
            $table->unsignedBigInteger('order_type_id');
            $table->decimal('cost_price', 10, 2)->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('package_charge', 10, 2)->nullable();
            $table->boolean('have_deposit_item')->default(false);
            $table->unsignedBigInteger('deposit_item_id')->nullable();
            $table->decimal('deposit_amount', 10, 2)->nullable();
            $table->boolean('is_synced')->default(0);
            $table->timestamp('sync_date_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_prices');
    }
};
