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
        Schema::create('advances', function (Blueprint $table) {
            $table->id();
            $table->string('advance_number')->nullable()->unique();

            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('reservation_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('payment_method_id')->nullable();
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('order_type_id');

            $table->enum('type', ['customer', 'staff']);
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->enum('direction', ['in', 'out']);

            $table->decimal('amount', 10, 2);
            $table->decimal('balance_amount', 10, 2)->nullable();

            $table->string('purpose')->nullable();
            $table->date('advance_date')->nullable();
            $table->date('event_date')->nullable();
            $table->text('notes')->nullable();

            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');

            $table->boolean('is_cancelled')->default(false);
            $table->unsignedBigInteger('cancelled_by')->nullable();
            $table->string('cancellation_reason')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advances');
    }
};
