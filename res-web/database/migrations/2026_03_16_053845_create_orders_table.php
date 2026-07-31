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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->integer('sub_order_id')->nullable();
            $table->string('order_number')->unique();

            $table->unsignedBigInteger('table_id')->nullable();
            $table->unsignedBigInteger('table_merge_id')->nullable();
            
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('steward_id')->constrained('users')->nullable();

            $table->unsignedBigInteger('order_type_id');

            $table->string('customer_name')->nullable();
            $table->string('customer_id')->nullable();
            $table->string('status');
            $table->integer('reserved_seats')->nullable();

            $table->decimal('total_amount', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('service_charge', 10, 2)->default(0);
            $table->decimal('packaging_charge', 10, 2)->default(0);

            $table->string('payment_status')->default('pending');
            $table->string('payment_method')->nullable();

            $table->timestamp('opened_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('prepared_at')->nullable();
            $table->timestamp('served_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            $table->text('notes')->nullable();

            $table->foreignId('cashier_id')->nullable()->constrained('users');
            $table->foreignId('opened_by')->nullable()->constrained('users');
            $table->foreignId('closed_by')->nullable()->constrained('users');

            $table->text('void_reason')->nullable();
            $table->text('cancel_reason')->nullable();

            $table->foreignId('cancelled_by')->nullable()->constrained('users');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
