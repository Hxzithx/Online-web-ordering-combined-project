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
        Schema::create('table_merges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('table_id_1');
            $table->unsignedBigInteger('table_id_2');
            $table->unsignedBigInteger('table_id_3')->nullable();
            $table->timestamp('merged_at')->useCurrent();
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variants');
    }
};
