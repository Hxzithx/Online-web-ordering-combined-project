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
        Schema::create('extra_services', function (Blueprint $table) {
            $table->id();
            $table->string('serviceName');
            $table->text('description')->nullable();
            $table->decimal('default_price',10, 2);
            $table->decimal('cost_price', 10, 2)->nullable();
            $table->boolean('is_tax_included')->default(false);
            $table->boolean('is_service_charge_included')->default(false);
            $table->string('applicable_order_types_id');
            $table->boolean('is_active')->default(true);
            $table->string('measure_unit')->nullable();
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
        Schema::dropIfExists('extra_services');
    }
};
