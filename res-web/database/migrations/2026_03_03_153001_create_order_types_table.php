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
        Schema::create('order_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('requires_table')->default(false);
            $table->boolean('allow_service_charge')->default(false);
            $table->boolean('allow_packaging_charge')->default(false);
            $table->boolean('allow_split_payment')->default(false);
            $table->boolean('allow_discounts')->default(false);
            $table->boolean('tax_inclusive')->default(false);
            $table->boolean('steward_required')->default(false);
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('order_types');
    }
};
