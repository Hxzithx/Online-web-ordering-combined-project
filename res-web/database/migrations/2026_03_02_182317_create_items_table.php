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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id');
            $table->foreignId('variant_id')->nullable();
            $table->foreignId('portions_id')->nullable();
            $table->string('item_type');
            $table->string('base_name');
            $table->string('full_name')->nullable();
            $table->text('description')->nullable();
            $table->string('status');
            $table->unsignedBigInteger('kitchen_id');
            $table->string('image_url')->nullable();
            $table->boolean('include_tax')->default(false);
            $table->boolean('include_service_charge')->default(false);
            $table->unsignedBigInteger('user_id')->constrained();
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
        Schema::dropIfExists('items');
    }
};
