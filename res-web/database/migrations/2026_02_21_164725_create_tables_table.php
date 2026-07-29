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
        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            $table->string('table_number')->unique();
            $table->unsignedBigInteger('capacity');
            $table->integer('reserved_seats')->default(0);
            $table->string('zone');
            $table->string('status')->default('available');
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->string('table_type')->default('standard');
            $table->integer('min_occupancy')->nullable();
            $table->integer('max_occupancy')->nullable();
            $table->json('features')->nullable();
            $table->foreignId('user_id')->constrained('users');
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
        Schema::dropIfExists('tables');
    }
};
