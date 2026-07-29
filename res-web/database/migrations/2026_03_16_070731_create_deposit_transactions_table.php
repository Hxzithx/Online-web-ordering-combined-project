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
        Schema::create('deposit_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cusromer_id');
            $table->unsignedBigInteger('deposit_item_id');
            $table->unsignedBigInteger('item_id')->nullable();
            $table->unsignedBigInteger('extra_service_id')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('deposit_amount', 8, 2);
            $table->date('return_date')->nullable();
            $table->decimal('refund_amount', 8, 2)->nullable();
            $table->date('refund_date')->nullable();
            $table->string('status')->default('active');
            $table->text('notes')->nullable();
            $table->boolean('isOverdue')->default(false);
            $table->string('refund_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposit_transactions');
    }
};
