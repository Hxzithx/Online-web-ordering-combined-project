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
        Schema::create('stewards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->string('name');
            $table->string('employee_id')->unique();
            $table->boolean('is_active')->default(true);
            $table->string('status')->default('available');
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
        Schema::dropIfExists('stewards');
    }
};
