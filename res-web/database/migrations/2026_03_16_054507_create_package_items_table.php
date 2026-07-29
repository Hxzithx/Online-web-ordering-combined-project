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
        Schema::create('package_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained()->cascadeOnDelete();
            $table->foreignId('item_id')->nullable()->constrained();
            $table->unsignedBigInteger('extra_service_id')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('cost_price', 10, 2)->nullable();
            $table->decimal('price', 10, 2);
            $table->string('status')->default('active');
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
        Schema::dropIfExists('package_items');
    }
};
